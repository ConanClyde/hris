<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $isHr = $user?->isHr();

        $userQuery = User::query();
        $leaveQuery = LeaveApplication::query();
        $trainingQuery = Training::query();

        if ($isHr) {
            $userQuery->where('role', '!=', UserRole::Admin->value);
            $leaveQuery->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
            $trainingQuery->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        $totalUsers = $userQuery->count();
        $summary = [
            'totalUsers' => $totalUsers,
        ];

        if ($isHr) {
            $summary['totalLeave'] = $leaveQuery->count();
            $summary['totalTraining'] = $trainingQuery->count();
            $summary['pendingLeave'] = (clone $leaveQuery)->where('status', 'pending')->count();
        }

        $page = $isHr ? 'HR/Reports/Index' : 'Admin/Reports/Index';

        return Inertia::render($page, [
            'summary' => $summary,
        ]);
    }
}
