<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

//login route
Route::get('login', function () {
    return view('auth.login');
})->name('login');

//register route
Route::get('register', function () {
    return view('auth.register');
})->name('register');
//Doctor
Route::get('/add_doctor_view', [AdminController::class, 'addview']);
//doctor post route
Route::post('/upload_doctor', [AdminController::class, 'upload']);

//Appointment route
Route::post('/appointment', [HomeController::class, 'appointment']);
//my-appointment
Route::get('/my-appointment', [HomeController::class, 'myAppointment'])->name('my-appointment');

//Approve appointment
Route::get('/approved/{id}', [AdminController::class, 'approveAppointment'])->name('approved');

//Canceled appointment
Route::get('/canceled/{id}', [AdminController::class, 'cancelAppointment'])->name('canceled');
//Delete Doctor
Route::get('/delete_doctor/{id}', [AdminController::class, 'deleteDoctor'])->name('delete_doctor');

//Show appointment for admin
Route::get('/show_appointment', [AdminController::class, 'showAppointment'])->name('show_appointment');

//show doctors for admin
Route::get('/show_doctors', [AdminController::class, 'showDoctors'])->name('show_doctors');

//Show update doctor for admin
Route::get('/update_doctor/{id}', [AdminController::class, 'updateDoctor'])->name('update_doctor'); 

//Edit doctor for admin
Route::post('/edit_doctor/{id}', [AdminController::class, 'editDoctor'])->name('edit_doctor');

//cancel appointment
Route::get('/cancel-appointment/{id}', [HomeController::class, 'cancelAppointment'])->name('cancel-appointment');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'redirect'])->name('dashboard');
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
});
