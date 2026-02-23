<?php

use App\Features\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Role-specific dashboards
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:hr'])->group(function () {
    Route::get('/hr/dashboard', [DashboardController::class, 'hr'])->name('hr.dashboard');
});

Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
});
