<?php

use App\Features\Leave\Http\Controllers\Api\LeaveApiController;
use App\Features\Notices\Http\Controllers\Api\NoticeApiController;
use App\Features\Training\Http\Controllers\Api\TrainingApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['integration'])
    ->prefix('v1')
    ->group(function () {
        // Leave Applications
        Route::get('leave-applications', [LeaveApiController::class, 'index']);
        Route::post('leave-applications', [LeaveApiController::class, 'store']);
        Route::get('leave-applications/{id}', [LeaveApiController::class, 'show']);
        Route::put('leave-applications/{id}/status', [LeaveApiController::class, 'updateStatus']);

        // Trainings
        Route::get('trainings', [TrainingApiController::class, 'index']);
        Route::put('trainings/{id}/status', [TrainingApiController::class, 'updateStatus']);

        // Notices
        Route::get('notices/active', [NoticeApiController::class, 'active']);

        // Organizational Structure
        Route::get('organizational-structure', [\App\Features\Employees\Http\Controllers\Api\StructureController::class, 'index']);
    });
