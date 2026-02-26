<?php

use App\Features\Auth\Http\Controllers\ProfileController;
use App\Features\Calendar\Http\Controllers\HR\CalendarController as HrCalendarController;
use App\Features\Leave\Http\Controllers\HR\LeaveController as HrLeaveController;
use App\Features\Leave\Http\Controllers\HR\LeaveCreditController;
use App\Features\Notices\Http\Controllers\HR\NoticeController as HrNoticeController;
use App\Features\Notifications\Http\Controllers\NotificationController;
use App\Features\Pds\Http\Controllers\HR\PdsController as HrPdsController;
use App\Features\Training\Http\Controllers\HR\TrainingController as HrTrainingController;
use App\Features\Users\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// HR routes
Route::prefix('hr')->group(function () {
    Route::middleware(['auth', 'role:hr'])->group(function () {
        // Calendar
        Route::get('/calendar', [HrCalendarController::class, 'index'])->name('hr.calendar');
        Route::get('/calendar/events', [HrCalendarController::class, 'events'])->name('hr.calendar.events');

        // Reports
        Route::get('/reports', function () {
            return Inertia::render('HR/Reports/Index');
        })->name('hr.reports');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('hr.notifications');
        Route::post('/notifications/{noticeId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('hr.notifications.mark-as-read');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('hr.notifications.unread-count');

        // Settings & profile
        Route::get('/settings', function () {
            return Inertia::render('HR/Settings/Index');
        })->name('hr.settings');

        Route::get('/profile', [ProfileController::class, 'show'])->name('hr.profile');

        Route::match(['PUT', 'PATCH'], '/profile', [ProfileController::class, 'update'])->name('hr.profile.update');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('hr.profile.password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('hr.profile.delete');

        // Employee list / PDS management
        Route::redirect('/employees', '/hr/pds');
        Route::get('/pds', [HrPdsController::class, 'index'])->name('hr.pds.index');
        Route::get('/pds/preview', [HrPdsController::class, 'preview'])->name('hr.pds.preview');
        Route::post('/pds/status', [HrPdsController::class, 'updateStatus'])->name('hr.pds.status');

        // User management (reuse Admin UserController)
        Route::get('/users/{status?}', [UserController::class, 'index'])
            ->where('status', 'pending|rejected|active|inactive')
            ->name('hr.users.index');
        Route::post('/users', [UserController::class, 'store'])->name('hr.users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('hr.users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('hr.users.destroy');
        Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('hr.users.approve');
        Route::patch('/users/{id}/reject', [UserController::class, 'reject'])->name('hr.users.reject');
        Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('hr.users.toggle-status');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('hr.users.bulk_action');

        // Training
        Route::get('/training', [HrTrainingController::class, 'index'])->name('hr.training.index');
        Route::post('/training', [HrTrainingController::class, 'store'])->name('hr.training.store');
        Route::put('/training/{id}', [HrTrainingController::class, 'update'])->name('hr.training.update');
        Route::delete('/training/{id}', [HrTrainingController::class, 'destroy'])->name('hr.training.destroy');

        // Leave Management
        Route::get('/leave-applications', [HrLeaveController::class, 'index'])->name('hr.leave-applications.index');
        Route::post('/leave-applications', [HrLeaveController::class, 'store'])->name('hr.leave-applications.store');
        Route::put('/leave-applications/{id}', [HrLeaveController::class, 'update'])->name('hr.leave-applications.update');
        Route::delete('/leave-applications/{id}', [HrLeaveController::class, 'destroy'])->name('hr.leave-applications.destroy');
        Route::get('/leave-applications/export', [HrLeaveController::class, 'export'])->name('hr.leave-applications.export');
        Route::delete('/leave-attachments/{id}', [HrLeaveController::class, 'destroyAttachment'])->name('hr.leave-attachments.destroy');

        // Leave Credits
        Route::get('/leave-credits', [LeaveCreditController::class, 'index'])->name('hr.leave-credits.index');
        Route::get('/leave-credits/{id}', [LeaveCreditController::class, 'show'])->name('hr.leave-credits.show');
    });
});

// HR notices
Route::middleware(['auth', 'role:hr'])->group(function () {
    Route::get('/hr/notices', [HrNoticeController::class, 'index'])->name('hr.notices.index');
    Route::get('/hr/notices/create', [HrNoticeController::class, 'create'])->name('hr.notices.create');
    Route::post('/hr/notices', [HrNoticeController::class, 'store'])->name('hr.notices.store');
    Route::get('/hr/notices/{id}/edit', [HrNoticeController::class, 'edit'])->name('hr.notices.edit');
    Route::put('/hr/notices/{id}', [HrNoticeController::class, 'update'])->name('hr.notices.update');
    Route::delete('/hr/notices/{id}', [HrNoticeController::class, 'destroy'])->name('hr.notices.destroy');
});
