<?php

use App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController;
use App\Features\Auth\Http\Controllers\ProfileController;
use App\Features\Calendar\Http\Controllers\Employee\CalendarController as EmployeeCalendarController;
use App\Features\Leave\Http\Controllers\Employee\LeaveController as EmployeeLeaveController;
use App\Features\Notifications\Http\Controllers\NotificationController;
use App\Features\Pds\Http\Controllers\Employee\PdsController as EmployeePdsController;
use App\Features\Posts\Http\Controllers\Employee\PostController as EmployeePostController;
use App\Features\Training\Http\Controllers\Employee\TrainingController as EmployeeTrainingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Employee-scoped routes
Route::prefix('employee')->group(function () {
    Route::middleware(['auth', 'role:employee'])->group(function () {
        // Calendar
        Route::get('/calendar', [EmployeeCalendarController::class, 'index'])->name('employee.calendar');
        Route::get('/calendar/events', [EmployeeCalendarController::class, 'events'])->name('employee.calendar.events');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('employee.notifications');
        Route::post('/notifications/{noticeId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('employee.notifications.mark-as-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('employee.notifications.mark-all-read');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('employee.notifications.unread-count');
        Route::delete('/notifications/{noticeId}', [NotificationController::class, 'destroy'])->name('employee.notifications.destroy');
        Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('employee.notifications.destroy-all');

        // Posts / Announcements feed
        Route::get('/posts', [EmployeePostController::class, 'index'])->name('employee.posts.index');
        Route::post('/posts/{post}/react', [EmployeePostController::class, 'react'])->name('employee.posts.react');
        Route::post('/posts/{post}/comments', [EmployeePostController::class, 'comment'])->name('employee.posts.comment');

        Route::get('/activity-logs', [ActivityLogsController::class, 'userIndex'])
            ->name('employee.activity-logs.index');

        // Settings & profile
        Route::get('/settings', function () {
            return Inertia::render('Employee/Settings/Index');
        })->name('employee.settings');

        Route::get('/profile', [ProfileController::class, 'show'])->name('employee.profile');

        Route::match(['PUT', 'PATCH'], '/profile', [ProfileController::class, 'update'])->name('employee.profile.update');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('employee.profile.password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('employee.profile.delete');

        // PDS
        Route::get('/pds', [EmployeePdsController::class, 'index'])->name('employee.pds.index');
        Route::post('/pds', [EmployeePdsController::class, 'store'])->name('employee.pds.store');
        Route::get('/pds/preview', [EmployeePdsController::class, 'preview'])->name('employee.pds.preview');
        Route::get('/pds/export', [\App\Features\Pds\Http\Controllers\PdsExportController::class, 'exportEmployee'])->name('employee.pds.export');

        // Training
        Route::get('/training', [EmployeeTrainingController::class, 'index'])->name('employee.training.index');
        Route::post('/training', [EmployeeTrainingController::class, 'store'])->name('employee.training.store');
        Route::put('/training/{id}', [EmployeeTrainingController::class, 'update'])->name('employee.training.update');
        Route::delete('/training/{id}', [EmployeeTrainingController::class, 'destroy'])->name('employee.training.destroy');
        Route::get('/training/export', [EmployeeTrainingController::class, 'export'])->name('employee.training.export');
        Route::delete('/training/attachment/{id}', [EmployeeTrainingController::class, 'deleteAttachment'])->name('employee.training.attachment.delete');

        // Leave applications
        Route::get('/leave-applications', [EmployeeLeaveController::class, 'index'])->name('employee.leave-applications.index');
        Route::post('/leave-applications', [EmployeeLeaveController::class, 'store'])->name('employee.leave-applications.store');
        Route::put('/leave-applications/{id}', [EmployeeLeaveController::class, 'update'])->name('employee.leave-applications.update');
        Route::delete('/leave-applications/{id}', [EmployeeLeaveController::class, 'destroy'])->name('employee.leave-applications.destroy');
        Route::get('/leave-applications/export', [EmployeeLeaveController::class, 'export'])->name('employee.leave-applications.export');
        Route::delete('/leave-attachments/{id}', [EmployeeLeaveController::class, 'destroyAttachment'])->name('employee.leave-attachments.destroy');
    });
});
