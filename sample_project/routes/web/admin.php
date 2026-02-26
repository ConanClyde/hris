<?php

use App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController;
use App\Features\Auth\Http\Controllers\ProfileController;
use App\Features\Auth\Http\Controllers\SettingsController;
use App\Features\Backup\Http\Controllers\Admin\BackupController;
use App\Features\Calendar\Http\Controllers\Admin\CalendarController as AdminCalendarController;
use App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController;
use App\Features\Notices\Http\Controllers\Admin\NoticeController;
use App\Features\Notifications\Http\Controllers\NotificationController;
use App\Features\Users\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PerfMetricsController;
use Illuminate\Support\Facades\Route;

// Admin calendar
Route::middleware(['session.auth', 'role:admin'])->group(function () {
    Route::get('/admin/calendar', [AdminCalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/admin/calendar/events', [AdminCalendarController::class, 'events'])->name('admin.calendar.events');

    // Custom Holidays Management
    Route::resource('/admin/custom-holidays', CustomHolidayController::class, [
        'names' => [
            'index' => 'admin.custom-holidays.index',
            'create' => 'admin.custom-holidays.create',
            'store' => 'admin.custom-holidays.store',
            'show' => 'admin.custom-holidays.show',
            'edit' => 'admin.custom-holidays.edit',
            'update' => 'admin.custom-holidays.update',
            'destroy' => 'admin.custom-holidays.destroy',
        ],
    ])->except(['create', 'show', 'edit']);

    // Admin user management
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
    Route::get('/admin/performance', [PerfMetricsController::class, 'index'])->name('admin.performance.index');

    // Reports & analytics
    Route::get('/admin/reports', function () {
        return view('admin.reports.index');
    })->name('admin.reports');

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

    // Settings & profile (admin shell)
    Route::get('/admin/settings', function () {
        return view('admin.settings.index');
    })->name('admin.settings');

    Route::post('/admin/settings/sessions/revoke', [SettingsController::class, 'revokeSession'])->name('settings.sessions.revoke');

    Route::get('/admin/profile', function () {
        return view('admin.profile.index');
    })->name('admin.profile');

    Route::match(['PUT', 'PATCH'], '/admin/profile', function (\Illuminate\Http\Request $request) {
        return app(ProfileController::class)->update($request);
    })->name('admin.profile.update');

    Route::post('/admin/profile/password', [ProfileController::class, 'changePassword'])->name('admin.profile.password');

    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.delete');

    // Admin PDS proxies into HR PDS
    Route::redirect('/admin/employees', '/admin/pds');

    Route::get('/admin/pds', function () {
        return redirect()->route('hr.pds.index');
    })->name('admin.pds');

    Route::get('/admin/pds/preview', function (\Illuminate\Http\Request $request) {
        return redirect()->route('hr.pds.preview', $request->query());
    })->name('admin.pds.preview');

    Route::post('/admin/pds/status', function (\Illuminate\Http\Request $request) {
        $status = $request->input('status');
        $userId = $request->input('user_id');

        return redirect()->back()->with('success', "PDS for {$userId} has been ".ucfirst($status).' (Mock).');
    })->name('admin.pds.status');

    // Admin leave (frontend stub)
    Route::get('/admin/leave', function () {
        return view('admin.leave.index');
    })->name('admin.leave');

    // Admin & HR notices share controllers
    Route::get('/admin/notices', [NoticeController::class, 'index'])->name('admin.notices.index');
    Route::get('/admin/notices/create', [NoticeController::class, 'create'])->name('admin.notices.create');
    Route::post('/admin/notices', [NoticeController::class, 'store'])->name('admin.notices.store');
    Route::get('/admin/notices/{id}/edit', [NoticeController::class, 'edit'])->name('admin.notices.edit');
    Route::put('/admin/notices/{id}', [NoticeController::class, 'update'])->name('admin.notices.update');
    Route::delete('/admin/notices/{id}', [NoticeController::class, 'destroy'])->name('admin.notices.destroy');

    // Admin notifications
    Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications');
});
