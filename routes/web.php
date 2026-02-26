<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Generic /dashboard redirects to role-appropriate dashboard
Route::get('dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isHr()) {
        return redirect()->route('hr.dashboard');
    }

    return redirect()->route('employee.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Attendance / DTR Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/attendance/clock-in', [\App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [\App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendance/history', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.history');
});

if (Features::enabled(Features::registration())) {
    Route::get('/register', [RegisterController::class, 'create'])
        ->middleware(['guest'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware(['guest'])
        ->name('register.store');
}

require __DIR__.'/settings.php';
require __DIR__.'/web/dashboard.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/hr.php';
require __DIR__.'/web/employee.php';
