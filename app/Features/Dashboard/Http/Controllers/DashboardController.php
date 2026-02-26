<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Leave\Models\LeaveApplication;
use App\Features\Pds\Models\Pds;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function admin(Request $request): \Inertia\Response
    {
        $totalUsers = User::count();
        $pendingCount = (int) User::where('status', 'pending')->count();

        $roles = User::selectRaw('role, count(*) as aggregate')
            ->groupBy('role')
            ->pluck('aggregate', 'role');

        $usersByRole = [
            'Admin' => (int) ($roles['admin'] ?? 0),
            'HR' => (int) ($roles['hr'] ?? 0),
            'Employee' => (int) ($roles['employee'] ?? 0),
        ];

        $statuses = User::selectRaw('is_active, count(*) as aggregate')
            ->groupBy('is_active')
            ->pluck('aggregate', 'is_active');

        $usersByStatus = [
            'active' => (int) ($statuses[true] ?? 0),
            'inactive' => (int) ($statuses[false] ?? 0),
        ];

        $user = $request->user();

        return Inertia::render('Admin/Dashboard', compact(
            'totalUsers',
            'pendingCount',
            'usersByRole',
            'usersByStatus'
        ) + [
            'user' => $user ? ['first_name' => $user->first_name ?? 'User'] : null,
        ]);
    }

    public function hr(Request $request): \Inertia\Response
    {
        $user = $request->user();

        // Exclude admins from counts for HR
        $totalUsers = User::where('role', '!=', 'admin')->count();

        $pendingLeaveCount = LeaveApplication::where('status', 'pending')
            ->whereHas('employee.user', function ($query) {
                $query->where('role', '!=', 'admin');
            })->count();

        $pendingTrainingCount = Training::where('status', 'pending')
            ->whereHas('employee.user', function ($query) {
                $query->where('role', '!=', 'admin');
            })->count();

        $pdsPendingCount = Pds::where('status', 'submitted')
            ->whereHas('employee.user', function ($query) {
                $query->where('role', '!=', 'admin');
            })->count();

        return Inertia::render('HR/Dashboard', [
            'totalUsers' => $totalUsers,
            'pendingLeaveCount' => $pendingLeaveCount,
            'pendingTrainingCount' => $pendingTrainingCount,
            'pdsPendingCount' => $pdsPendingCount,
            'user' => $user ? ['first_name' => $user->first_name ?? 'User'] : null,
        ]);
    }

    public function employee(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $employeeId = $user?->employee?->id;

        $leaveCount = 0;
        $trainingCount = 0;
        $pdsStatus = null;
        if ($employeeId !== null) {
            $leaveCount = LeaveApplication::where('employee_id', $employeeId)->count();
            $trainingCount = Training::where('employee_id', $employeeId)->count();
            $pds = Pds::where('employee_id', $employeeId)->first();
            $pdsStatus = $pds?->status ?? null;
        }

        return Inertia::render('Employee/Dashboard', [
            'leaveCount' => $leaveCount,
            'trainingCount' => $trainingCount,
            'pdsStatus' => $pdsStatus,
            'user' => $user ? ['first_name' => $user->first_name ?? 'User'] : null,
        ]);
    }
}
