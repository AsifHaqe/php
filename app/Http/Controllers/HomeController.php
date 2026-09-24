<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;


class HomeController extends Controller
{
    public function redirect()
    {
        if (Auth::check()) {
            if (Auth::user()->usertype == '1') {
                return view('admin.home');
            }

            $doctors = Doctor::all();
            return view('dashboard', compact('doctors'));
        }

        return redirect()->route('login');
    }
    public function index(){
        $doctors = User::where('usertype', '0')->get();
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
