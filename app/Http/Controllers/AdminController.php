<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    public function addview()
    {
        return view('admin.add_doctor');
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'doctor_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'speciality' => ['required', 'string', 'max:120'],
            'room_number' => ['required', 'string', 'max:30'],
            'clinic_address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'credentials_verified' => ['sometimes', 'boolean'],
            'available' => ['sometimes', 'boolean'],
            'doctor_image' => ['required', 'image', 'max:5120'],
        ]);

        $doctor = new Doctor;
        $doctor->name = $validated['doctor_name'];
        $doctor->phone = $validated['phone'];
        $doctor->speciality = $validated['speciality'];
        $doctor->room_number = $validated['room_number'];
        $doctor->clinic_address = $validated['clinic_address'];
        $doctor->latitude = $validated['latitude'];
        $doctor->longitude = $validated['longitude'];
        $doctor->credentials_verified = $request->boolean('credentials_verified');
        $doctor->available = $request->boolean('available');

        $directory = public_path('doctorimage');
        File::ensureDirectoryExists($directory);
        $filename = Str::uuid().'.'.$request->file('doctor_image')->extension();
        $request->file('doctor_image')->move($directory, $filename);
        $doctor->doctor_image = $filename;

        $doctor->save();
        return redirect()->back()->with('message', 'Doctor added successfully');
    }

    public function showAppointment()
    {
        $data = Appointment::all();
        return view('admin.show_appointment', compact('data'));
    }

    public function show_Appointment()
    {
        $data = Appointment::all();
        return view('admin.show_appointment', compact('data'));
    }

    public function approveAppointment($id){
        $data = Appointment::find($id);
        $data->status = 'approved';
        $data->save();
        return redirect()->back();
    }
    public function cancelAppointment($id){
        $data = Appointment::find($id);
        $data->status = 'canceled';
        $data->save();
        return redirect()->back();
    }

    public function completeAppointment($id)
    {
        $appointment = Appointment::where('status', 'approved')->findOrFail($id);
        $appointment->status = 'completed';
        $appointment->save();

        return redirect()->back()->with('message', 'Appointment marked as completed.');
    }

    public function showDoctors(){
        return view('admin.show_doctors', ['doctors' => Doctor::all()]);
    }

    public function deleteDoctor($id){
        $doctor = Doctor::find($id);
        if ($doctor) {
            $doctor->delete();
            return redirect()->back()->with('message', 'Doctor deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Doctor not found');
        }
    }

    //update doctor
    public function updateDoctor($id){
        return view('admin.update_doctor', ['doctor' => Doctor::find($id)]);
    }
    //edit doctor
    public function editDoctor(Request $request, $id){
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'speciality' => ['required', 'string', 'max:120'],
            'room_number' => ['required', 'string', 'max:30'],
            'clinic_address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'credentials_verified' => ['sometimes', 'boolean'],
            'available' => ['sometimes', 'boolean'],
            'doctor_image' => ['nullable', 'image', 'max:5120'],
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->name = $validated['name'];
        $doctor->phone = $validated['phone'];
        $doctor->speciality = $validated['speciality'];
        $doctor->room_number = $validated['room_number'];
        $doctor->clinic_address = $validated['clinic_address'];
        $doctor->latitude = $validated['latitude'];
        $doctor->longitude = $validated['longitude'];
        $doctor->credentials_verified = $request->boolean('credentials_verified');
        $doctor->available = $request->boolean('available');

        if ($request->hasFile('doctor_image')) {
            $directory = public_path('doctorimage');
            File::ensureDirectoryExists($directory);
            $filename = Str::uuid().'.'.$request->file('doctor_image')->extension();
            $request->file('doctor_image')->move($directory, $filename);
            $doctor->doctor_image = $filename;
        }

        $doctor->save();

        return redirect()->back()->with('message', 'Doctor updated successfully');
    }
}