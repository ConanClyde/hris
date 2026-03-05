<?php

use App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController;
use App\Features\Auth\Http\Controllers\ProfileController;
use App\Features\Calendar\Http\Controllers\HR\CalendarController as HrCalendarController;
use App\Features\Leave\Http\Controllers\HR\LeaveController as HrLeaveController;
use App\Features\Leave\Http\Controllers\HR\LeaveCreditController;
use App\Features\Leave\Http\Controllers\HR\LeaveReportsController;
use App\Features\Notifications\Http\Controllers\NotificationController;
use App\Features\Pds\Http\Controllers\HR\PdsController as HrPdsController;
use App\Features\Posts\Http\Controllers\HR\PostController as HrPostController;
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
        Route::get('/reports', [\App\Features\Dashboard\Http\Controllers\ReportsController::class, 'index'])->name('hr.reports');
        Route::get('/reports/custom', [\App\Features\Dashboard\Http\Controllers\CustomReportController::class, 'index'])->name('hr.reports.custom');
        Route::get('/reports/custom/export', [\App\Features\Dashboard\Http\Controllers\CustomReportController::class, 'export'])->name('hr.reports.custom.export');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('hr.notifications');
        Route::post('/notifications/{noticeId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('hr.notifications.mark-as-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('hr.notifications.mark-all-read');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('hr.notifications.unread-count');
        Route::delete('/notifications/{noticeId}', [NotificationController::class, 'destroy'])->name('hr.notifications.destroy');
        Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('hr.notifications.destroy-all');

        // Activity Logs
        Route::get('/activity-logs', [ActivityLogsController::class, 'userIndex'])->name('hr.activity-logs.index');

        // Posts / Announcements
        Route::get('/posts', [HrPostController::class, 'index'])->name('hr.posts.index');
        Route::post('/posts', [HrPostController::class, 'store'])->name('hr.posts.store');
        Route::post('/posts/{post}/react', [HrPostController::class, 'react'])->name('hr.posts.react');
        Route::post('/posts/{post}/comments', [HrPostController::class, 'comment'])->name('hr.posts.comment');
        Route::put('/posts/{post}', [HrPostController::class, 'update'])->name('hr.posts.update');
        Route::delete('/posts/{post}', [HrPostController::class, 'destroy'])->name('hr.posts.destroy');

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
        Route::get('/pds/preview-json', [HrPdsController::class, 'previewJson'])->name('hr.pds.preview-json');
        Route::post('/pds/status', [HrPdsController::class, 'updateStatus'])->name('hr.pds.status');
        Route::post('/pds/revisions/{id}/approve', [HrPdsController::class, 'approveRevision'])->name('hr.pds.revisions.approve');
        Route::post('/pds/revisions/{id}/reject', [HrPdsController::class, 'rejectRevision'])->name('hr.pds.revisions.reject');
        Route::get('/pds/{id}/export', [\App\Features\Pds\Http\Controllers\PdsExportController::class, 'exportHr'])->name('hr.pds.export');

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

        // Leave Reports
        Route::get('/reports/leave', [LeaveReportsController::class, 'index'])->name('hr.reports.leave');
        Route::get('/reports/leave/export', [LeaveReportsController::class, 'export'])->name('hr.reports.leave.export');

        // Organizational Chart
        Route::get('/organizational-chart', function () {
            $structure = \App\Features\Employees\Models\Division::with(['subdivisions.sections', 'sections'])
                ->get()
                ->map(function ($division) {
                    return [
                        'id' => $division->id,
                        'name' => $division->name,
                        'subdivisions' => $division->subdivisions->map(function ($sub) {
                            return [
                                'id' => $sub->id,
                                'name' => $sub->name,
                                'sections' => $sub->sections->map(function ($sec) {
                                    return [
                                        'id' => $sec->id,
                                        'name' => $sec->name,
                                    ];
                                }),
                            ];
                        }),
                        // Only show sections directly under this division (not under a subdivision)
                        'sections' => $division->sections->filter(function ($sec) {
                            return $sec->subdivision_id === null;
                        })->values()->map(function ($sec) {
                            return [
                                'id' => $sec->id,
                                'name' => $sec->name,
                            ];
                        }),
                    ];
                });

            // Get employee counts by organizational unit
            $employeeCounts = \App\Features\Employees\Models\Employee::selectRaw('
                    division_id,
                    subdivision_id,
                    section_id,
                    COUNT(*) as count
                ')
                ->groupBy('division_id', 'subdivision_id', 'section_id')
                ->get()
                ->map(function ($row) {
                    return [
                        'division_id' => $row->division_id,
                        'subdivision_id' => $row->subdivision_id,
                        'section_id' => $row->section_id,
                        'count' => $row->count,
                    ];
                });

            // Get employees with their organizational unit info
            $employees = \App\Features\Employees\Models\Employee::select(
                'id',
                'user_id',
                'first_name',
                'last_name',
                'division_id',
                'subdivision_id',
                'section_id'
            )
                ->get()
                ->map(function ($emp) {
                    return [
                        'id' => $emp->id,
                        'employee_id' => $emp->user_id,
                        'first_name' => $emp->first_name,
                        'last_name' => $emp->last_name,
                        'division_id' => $emp->division_id,
                        'subdivision_id' => $emp->subdivision_id,
                        'section_id' => $emp->section_id,
                    ];
                });

            return Inertia::render('HR/OrgChart/Index', [
                'structure' => $structure,
                'employeeCounts' => $employeeCounts,
                'employees' => $employees,
            ]);
        })->name('hr.orgchart');

        // AI Chatbot routes moved to web.php

        // Onboarding
        Route::get('/onboarding', [\App\Features\Dashboard\Http\Controllers\OnboardingController::class, 'index'])->name('hr.onboarding.index');
        Route::post('/onboarding/{employeeId}/init', [\App\Features\Dashboard\Http\Controllers\OnboardingController::class, 'initChecklist'])->name('hr.onboarding.init');
        Route::post('/onboarding/toggle/{id}', [\App\Features\Dashboard\Http\Controllers\OnboardingController::class, 'toggleItem'])->name('hr.onboarding.toggle');

        // Offboarding
        Route::get('/offboarding', [\App\Features\Dashboard\Http\Controllers\OnboardingController::class, 'offboardingIndex'])->name('hr.offboarding.index');
        Route::post('/offboarding/{employeeId}/init', [\App\Features\Dashboard\Http\Controllers\OnboardingController::class, 'initClearance'])->name('hr.offboarding.init');
        Route::post('/offboarding/clearance/{id}', [\App\Features\Dashboard\Http\Controllers\OnboardingController::class, 'updateClearance'])->name('hr.offboarding.update');
    });
});
