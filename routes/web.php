<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Generic /dashboard redirects to role-appropriate dashboard
Route::get('dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role ?? 'employee';
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($role === 'hr') {
        return redirect()->route('hr.dashboard');
    }

    return redirect()->route('employee.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
