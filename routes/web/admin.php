<?php

use App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController;
use App\Features\Auth\Http\Controllers\ProfileController;
use App\Features\Backup\Http\Controllers\Admin\BackupController;
use App\Features\Calendar\Http\Controllers\Admin\CalendarController as AdminCalendarController;
use App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController;
use App\Features\Dashboard\Http\Controllers\PerformanceController as AdminPerformanceController;
use App\Features\Dashboard\Http\Controllers\ReportsController as AdminReportsController;
use App\Features\Notifications\Http\Controllers\NotificationController;
use App\Features\Posts\Http\Controllers\Admin\PostController as AdminPostController;
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
    Route::get('/admin/users/{status?}', [UserController::class, 'index'])
        ->where('status', 'pending|rejected|active|inactive')
        ->name('admin.users');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{id}/approve', [UserController::class, 'approve'])->name('admin.users.approve');
    Route::patch('/admin/users/{id}/reject', [UserController::class, 'reject'])->name('admin.users.reject');
    Route::patch('/admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::post('/admin/users/bulk-action', [UserController::class, 'bulkAction'])->name('admin.users.bulk_action');

    // Activity logs
    Route::get('/admin/activity-logs', [ActivityLogsController::class, 'index'])->name('admin.activity-logs.index');
    Route::get('/admin/activity-logs/export', [ActivityLogsController::class, 'export'])->name('admin.activity-logs.export');

    // Performance metrics
    Route::get('/admin/performance', [AdminPerformanceController::class, 'index'])->name('admin.performance.index');
    Route::post('/admin/performance/diagnostics', [AdminPerformanceController::class, 'diagnostics'])->name('admin.performance.diagnostics');

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

    // Notifications
    Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications');
    Route::post('/admin/notifications/{noticeId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-as-read');
    Route::post('/admin/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');
    Route::get('/admin/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('admin.notifications.unread-count');
    Route::delete('/admin/notifications/{noticeId}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::delete('/admin/notifications', [NotificationController::class, 'destroyAll'])->name('admin.notifications.destroy-all');

    // Posts / Announcements
    Route::get('/admin/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::post('/admin/posts', [AdminPostController::class, 'store'])->name('admin.posts.store');
    Route::post('/admin/posts/{post}/react', [AdminPostController::class, 'react'])->name('admin.posts.react');
    Route::post('/admin/posts/{post}/comments', [AdminPostController::class, 'comment'])->name('admin.posts.comment');
    Route::put('/admin/posts/{post}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/admin/posts/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');

    // Settings & Profile
    Route::get('/admin/settings', function () {
        return Inertia::render('Admin/Settings/Index');
    })->name('admin.settings');

    Route::get('/admin/profile', [ProfileController::class, 'show'])->name('admin.profile');

    Route::match(['PUT', 'PATCH'], '/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [ProfileController::class, 'changePassword'])->name('admin.profile.password');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.delete');

    // Reports
    Route::get('/admin/reports', [AdminReportsController::class, 'index'])->name('admin.reports');

    Route::get('/admin/reports/export/analytics', [AdminReportsController::class, 'exportAdminAnalytics'])
        ->name('admin.reports.export.analytics');
});
