<?php

use App\Features\Dashboard\Http\Controllers\DashboardController;
use App\Features\Notices\Http\Controllers\Api\NoticeApiController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\PerfMetricsController;
use Illuminate\Support\Facades\Route;

// Current user profile API and SSE (session auth for same-origin requests)
Route::middleware(['web', 'session.auth'])->group(function () {
    Route::get('/api/me', [MeController::class, 'show'])->name('api.me');

    // Notification API
    Route::get('/api/notifications', [NoticeApiController::class, 'index'])->name('api.notifications.index');
    Route::post('/api/notifications/mark-read', [NoticeApiController::class, 'markAllRead'])->name('api.notifications.markAllRead');
    Route::post('/api/notifications/{id}/read', [NoticeApiController::class, 'markRead'])->name('api.notifications.markRead');
});

Route::post('/api/perf-metrics', [PerfMetricsController::class, 'store'])->name('api.perf-metrics');

// Role-specific dashboards
Route::middleware(['session.auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});
Route::middleware(['session.auth', 'role:hr'])->group(function () {
    Route::get('/hr/dashboard', [DashboardController::class, 'hr'])->name('hr.dashboard');
});
Route::middleware(['session.auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
});

// Backward-compatible: /dashboard redirects to role-appropriate dashboard
Route::get('/dashboard', function () {
    if (! \Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('login');
    }

    $role = \Illuminate\Support\Facades\Auth::user()->role ?? 'employee';
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($role === 'hr') {
        return redirect()->route('hr.dashboard');
    }

    return redirect()->route('employee.dashboard');
})->name('dashboard');
