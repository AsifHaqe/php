<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Appointment;


class AdminController extends Controller
{
    public function addview()
    {
        return view('admin.add_doctor');
    }

    public function upload(Request $request)
    {
        $doctor = new Doctor;
        $doctor->name = $request->doctor_name;
        $doctor->phone = $request->phone;
        $doctor->speciality = $request->speciality;
        $doctor->room_number = $request->room_number;

        if($request->hasfile('doctor_image'))
        {
            $file = $request->file('doctor_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move('doctorimage/', $filename);
            $doctor->doctor_image = $filename;
        }
        else
        {
            return $request;
            $doctor->doctor_image = '';
        }

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
        $doctor = Doctor::find($id);
        if ($doctor) {
            $doctor->name = $request->input('name', $doctor->name);
            $doctor->phone = $request->input('phone', $doctor->phone);
            $doctor->speciality = $request->input('speciality', $doctor->speciality);
            $doctor->room_number = $request->input('room_number', $doctor->room_number);

            if ($request->hasFile('doctor_image')) {
                $file = $request->file('doctor_image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('doctorimage/', $filename);
                $doctor->doctor_image = $filename;
            }

            $doctor->save();
        }
        return redirect()->back()->with('message', 'Doctor updated successfully');
    }
}