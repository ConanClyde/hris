<?php

use App\Features\Employees\Http\Controllers\Api\StructureController;
use App\Features\Leave\Http\Controllers\Api\LeaveApiController;
use App\Features\Notices\Http\Controllers\Api\NoticeApiController;
use App\Features\Training\Http\Controllers\Api\TrainingApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['integration'])
    ->prefix('v1')
    ->group(function () {
        Route::get('leave-applications', [LeaveApiController::class, 'index']);
        Route::post('leave-applications', [LeaveApiController::class, 'store']);
        Route::get('leave-applications/{id}', [LeaveApiController::class, 'show']);
        Route::put('leave-applications/{id}/status', [LeaveApiController::class, 'updateStatus']);

        Route::get('trainings', [TrainingApiController::class, 'index']);
        Route::put('trainings/{id}/status', [TrainingApiController::class, 'updateStatus']);

        Route::get('notices/active', [NoticeApiController::class, 'active']);

        Route::get('organizational-structure', [StructureController::class, 'index']);
    });
