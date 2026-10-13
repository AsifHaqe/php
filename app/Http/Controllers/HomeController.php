<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;


class HomeController extends Controller
{
    protected function isAdminUser($user): bool
    {
        return ((string) ($user->usertype ?? '0')) === '1'
            || strtolower((string) ($user->email ?? '')) === 'admin@hospital.com';
    }

    public function redirect()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isAdminUser(Auth::user())) {
            return redirect()->route('admin.dashboard');
        }

        $doctors = Schema::hasTable('doctors') ? Doctor::all() : collect();
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
        $doctors = Schema::hasTable('users') ? User::where('usertype', '0')->get() : collect();
        return view('dashboard', compact('doctors'));
    }
    //Appointment function
    public function appointment(Request $request){
        $data = new Appointment;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->doctor = $request->doctor;   
        $data->date = $request->date;
        $data->message = $request->message;
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
            $appoint = Appointment::where('user_id', $userid)->get();
            return view('my_appointment', compact('appoint'));
        }else {
            return redirect()->back();
        }

        return view ('my_appointment');
    }
    public function cancelAppointment($id){
        $data = Appointment::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Appointment cancelled successfully!');
    }
}
