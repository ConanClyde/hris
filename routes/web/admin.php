<?php

use App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController;
use App\Features\Auth\Http\Controllers\ProfileController;
use App\Features\Backup\Http\Controllers\Admin\BackupController;
use App\Features\Calendar\Http\Controllers\Admin\CalendarController as AdminCalendarController;
use App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController;
use App\Features\Notices\Http\Controllers\Admin\NoticeController;
use App\Features\Notifications\Http\Controllers\NotificationController;
use App\Features\Users\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Calendar
    Route::get('/admin/calendar', [AdminCalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/admin/calendar/events', [AdminCalendarController::class, 'events'])->name('admin.calendar.events');

    // Custom Holidays Management
    Route::resource('/admin/custom-holidays', CustomHolidayController::class, [
        'names' => [
            'index' => 'admin.custom-holidays.index',
            'store' => 'admin.custom-holidays.store',
            'update' => 'admin.custom-holidays.update',
            'destroy' => 'admin.custom-holidays.destroy',
        ],
    ])->except(['create', 'show', 'edit']);

    // User management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::post('/admin/users/bulk-action', [UserController::class, 'bulkAction'])->name('admin.users.bulk_action');

    // Activity logs
    Route::get('/admin/activity-logs', [ActivityLogsController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/admin/activity-logs/export', [ActivityLogsController::class, 'export'])->name('admin.activity-logs.export');

    // Performance metrics
    Route::get('/admin/performance', function () {
        return Inertia::render('Admin/Performance/Index');
    })->name('admin.performance.index');

    // Backup management
    Route::prefix('admin/backup')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('admin.backup.index');
        Route::post('/run', [BackupController::class, 'run'])->name('admin.backup.run');
        Route::post('/upload', [BackupController::class, 'upload'])->name('admin.backup.upload');
        Route::get('/{id}/download', [BackupController::class, 'download'])->name('admin.backup.download');
        Route::post('/{id}/restore', [BackupController::class, 'restore'])->name('admin.backup.restore');
        Route::put('/{id}', [BackupController::class, 'update'])->name('admin.backup.update');
        Route::delete('/{id}', [BackupController::class, 'destroy'])->name('admin.backup.destroy');
    });

    // Notices
    Route::get('/admin/notices', [NoticeController::class, 'index'])->name('admin.notices.index');
    Route::get('/admin/notices/create', [NoticeController::class, 'create'])->name('admin.notices.create');
    Route::post('/admin/notices', [NoticeController::class, 'store'])->name('admin.notices.store');
    Route::get('/admin/notices/{id}/edit', [NoticeController::class, 'edit'])->name('admin.notices.edit');
    Route::put('/admin/notices/{id}', [NoticeController::class, 'update'])->name('admin.notices.update');
    Route::delete('/admin/notices/{id}', [NoticeController::class, 'destroy'])->name('admin.notices.destroy');

    // Notifications
    Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications');

    // Settings & Profile
    Route::get('/admin/settings', function () {
        return Inertia::render('Admin/Settings/Index');
    })->name('admin.settings');

    Route::get('/admin/profile', [ProfileController::class, 'show'])->name('admin.profile');

    Route::match(['PUT', 'PATCH'], '/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [ProfileController::class, 'changePassword'])->name('admin.profile.password');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.delete');

    // Leave stub
    Route::get('/admin/leave', function () {
        return Inertia::render('Admin/Leave/Index');
    })->name('admin.leave');

    // Reports stub
    Route::get('/admin/reports', function () {
        return Inertia::render('Admin/Reports/Index');
    })->name('admin.reports');

    // PDS proxies
    Route::redirect('/admin/employees', '/admin/pds');
    Route::get('/admin/pds', function () {
        return Inertia::render('Admin/PDS/Index');
    })->name('admin.pds');
});
