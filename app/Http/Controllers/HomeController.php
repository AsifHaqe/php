<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorReview;
use App\Services\GeminiDoctorExplanationService;


class HomeController extends Controller
{
    protected function isAdminUser($user): bool
    {
        return ((string) ($user->usertype ?? '0')) === '1';
    }

    public function redirect()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isAdminUser(Auth::user())) {
            return redirect()->route('admin.dashboard');
        }

        $doctors = Schema::hasTable('doctors')
            ? Doctor::withAvg('reviews', 'rating')->withCount('reviews')->get()
            : collect();
        return view('dashboard', compact('doctors'));
    }

    public function adminDashboard()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! $this->isAdminUser(Auth::user())) {
            return redirect()->route('dashboard');
        }

        return view('admin.home');
    }

    public function index(){
        $doctors = Schema::hasTable('doctors')
            ? Doctor::withAvg('reviews', 'rating')->withCount('reviews')->get()
            : collect();
        return view('dashboard', compact('doctors'));
    }

    public function recommendDoctors(Request $request, GeminiDoctorExplanationService $gemini)
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'speciality' => ['nullable', 'string', 'max:120'],
        ]);

        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];
        $speciality = trim((string) ($validated['speciality'] ?? ''));
        $radiusKm = 25;

        $doctors = Doctor::query()
            ->where('credentials_verified', true)
            ->where('available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($speciality !== '', fn ($query) => $query->whereRaw('LOWER(speciality) = ?', [mb_strtolower($speciality)]))
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get()
            ->map(function (Doctor $doctor) use ($latitude, $longitude): Doctor {
                $doctor->distance_km = $this->distanceInKilometers(
                    $latitude,
                    $longitude,
                    (float) $doctor->latitude,
                    (float) $doctor->longitude,
                );

                return $doctor;
            })
            ->filter(fn (Doctor $doctor) => $doctor->distance_km <= $radiusKm)
            ->sort(function (Doctor $first, Doctor $second): int {
                $distanceOrder = $first->distance_km <=> $second->distance_km;

                if ($distanceOrder !== 0) {
                    return $distanceOrder;
                }

                $ratingOrder = (float) ($second->reviews_avg_rating ?? 0) <=> (float) ($first->reviews_avg_rating ?? 0);

                return $ratingOrder !== 0 ? $ratingOrder : $first->id <=> $second->id;
            })
            ->values()
            ->take(5);

        $ai = $doctors->isEmpty()
            ? [
                'available' => false,
                'message' => '',
                'reasons' => [],
            ]
            : $gemini->explain($doctors->take(5)->map(fn (Doctor $doctor): array => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'distance_km' => round($doctor->distance_km, 1),
                'rating' => $doctor->reviews_count > 0 ? round((float) $doctor->reviews_avg_rating, 1) : null,
                'review_count' => $doctor->reviews_count,
            ])->all());

        return view('doctors.recommendations', [
            'doctors' => $doctors,
            'ai' => $ai,
            'speciality' => $speciality,
            'radiusKm' => $radiusKm,
        ]);
    }
    //Appointment function
    public function appointment(Request $request){
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'doctor_id' => ['required', 'integer', 'exists:doctors,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $doctor = Doctor::whereKey($validated['doctor_id'])
            ->where('available', true)
            ->first();

        if (! $doctor) {
            return back()->withInput()->withErrors(['doctor_id' => 'This doctor is not currently available for appointments.']);
        }

        $data = new Appointment;
        $data->name = $validated['name'];
        $data->email = $validated['email'];
        $data->phone = $validated['phone'];
        $data->doctor_id = $doctor->id;
        $data->doctor = $doctor->name;
        $data->date = $validated['date'];
        $data->message = $validated['message'] ?? null;
        $data->status = 'In progress';
        if (Auth::id()) {
            $data->user_id = Auth::id();
        }

        $data->save();
        return redirect()->back()->with('message', 'Appointment request submitted successfully!');
    }

    public function myappointment(){
        if (Auth::id()) {
            $userid = Auth::id();
            $appoint = Appointment::with(['doctor', 'review'])->where('user_id', $userid)->get();
            return view('my_appointment', compact('appoint'));
        }else {
            return redirect()->back();
        }

        return view ('my_appointment');
    }
    public function cancelAppointment($id){
        $data = Appointment::where('user_id', Auth::id())
            ->where(fn ($query) => $query->whereNull('status')->orWhere('status', '!=', 'completed'))
            ->findOrFail($id);
        $data->delete();
        return redirect()->back()->with('message', 'Appointment cancelled successfully!');
    }

    public function submitDoctorReview(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        $appointment = Appointment::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereNotNull('doctor_id')
            ->with('review')
            ->findOrFail($id);

        if ($appointment->review) {
            return back()->withErrors(['review' => 'A review has already been submitted for this appointment.']);
        }

        DoctorReview::create([
            'doctor_id' => $appointment->doctor_id,
            'user_id' => Auth::id(),
            'appointment_id' => $appointment->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
        ]);

        return back()->with('message', 'Thank you. Your verified review has been submitted.');
    }

    private function distanceInKilometers(float $latitude, float $longitude, float $doctorLatitude, float $doctorLongitude): float
    {
        $earthRadiusKm = 6371;
        $latitudeDelta = deg2rad($doctorLatitude - $latitude);
        $longitudeDelta = deg2rad($doctorLongitude - $longitude);
        $a = sin($latitudeDelta / 2) ** 2
            + cos(deg2rad($latitude)) * cos(deg2rad($doctorLatitude)) * sin($longitudeDelta / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
