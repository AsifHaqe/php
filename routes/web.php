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

//Appointment route
Route::post('/appointment', [HomeController::class, 'appointment']);
Route::post('/doctor-recommendations', [HomeController::class, 'recommendDoctors'])
    ->middleware('throttle:10,1')
    ->name('doctor-recommendations');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'redirect'])->name('dashboard');
    Route::get('/my-appointment', [HomeController::class, 'myAppointment'])->name('my-appointment');
    Route::post('/cancel-appointment/{id}', [HomeController::class, 'cancelAppointment'])->name('cancel-appointment');
    Route::post('/appointments/{id}/review', [HomeController::class, 'submitDoctorReview'])->name('appointments.review');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/add_doctor_view', [AdminController::class, 'addview']);
        Route::post('/upload_doctor', [AdminController::class, 'upload']);
        Route::post('/approved/{id}', [AdminController::class, 'approveAppointment'])->name('approved');
        Route::post('/canceled/{id}', [AdminController::class, 'cancelAppointment'])->name('canceled');
        Route::post('/completed/{id}', [AdminController::class, 'completeAppointment'])->name('completed');
        Route::post('/delete_doctor/{id}', [AdminController::class, 'deleteDoctor'])->name('delete_doctor');
        Route::get('/show_appointment', [AdminController::class, 'showAppointment'])->name('show_appointment');
        Route::get('/show_doctors', [AdminController::class, 'showDoctors'])->name('show_doctors');
        Route::get('/update_doctor/{id}', [AdminController::class, 'updateDoctor'])->name('update_doctor');
        Route::post('/edit_doctor/{id}', [AdminController::class, 'editDoctor'])->name('edit_doctor');
    });
});
