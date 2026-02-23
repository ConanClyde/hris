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
    public function admin(Request $request)
    {
        $users = User::all();
        $totalUsers = $users->count();
        $pendingCount = User::where('is_active', false)->count();
        $usersByRole = [
            'Admin' => $users->where('role', 'admin')->count(),
            'HR' => $users->where('role', 'hr')->count(),
            'Employee' => $users->where('role', 'employee')->count(),
        ];
        $usersByStatus = [
            'active' => $users->where('is_active', true)->count(),
            'inactive' => $users->where('is_active', false)->count(),
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

    public function hr(Request $request)
    {
        $user = $request->user();

        return Inertia::render('HR/Dashboard', [
            'totalUsers' => User::count(),
            'pendingLeaveCount' => LeaveApplication::query()->where('status', 'pending')->count(),
            'pendingTrainingCount' => Training::query()->where('status', 'pending')->count(),
            'pdsPendingCount' => Pds::query()->where('status', 'submitted')->count(),
            'user' => $user ? ['first_name' => $user->first_name ?? 'User'] : null,
        ]);
    }

    public function employee(Request $request)
    {
        return Inertia::render('Employee/Dashboard');
    }
}
