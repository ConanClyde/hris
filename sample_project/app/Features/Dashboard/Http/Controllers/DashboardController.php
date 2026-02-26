<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getMockUsers()
    {
        return collect(range(1, 30))->map(function ($id) {
            return (object) [
                'id' => $id,
                'role' => $id === 1 ? 'Admin' : ($id % 2 === 0 ? 'HR' : 'Employee'),
                'status' => $id % 5 === 0 ? 'pending' : ($id % 7 === 0 ? 'rejected' : ($id % 3 === 0 ? 'inactive' : 'active')),
            ];
        });
    }

    public function admin(Request $request)
    {
        $users = $this->getMockUsers();
        $totalUsers = $users->count();
        $pendingCount = $users->where('status', 'pending')->count();
        $usersByRole = [
            'Admin' => $users->where('role', 'Admin')->count(),
            'HR' => $users->where('role', 'HR')->count(),
            'Employee' => $users->where('role', 'Employee')->count(),
        ];
        $usersByStatus = [
            'active' => $users->where('status', 'active')->count(),
            'pending' => $users->where('status', 'pending')->count(),
            'inactive' => $users->where('status', 'inactive')->count(),
            'rejected' => $users->where('status', 'rejected')->count(),
        ];

        return view('features.dashboard.admin.index', compact(
            'totalUsers',
            'pendingCount',
            'usersByRole',
            'usersByStatus'
        ));
    }

    public function hr(Request $request)
    {
        $users = $this->getMockUsers();
        $totalUsers = $users->count();

        return view('features.dashboard.hr.index', [
            'totalUsers' => $totalUsers,
            'pendingLeaveCount' => 0,
            'pendingTrainingCount' => 0,
            'pdsPendingCount' => 0,
        ]);
    }

    public function employee(Request $request)
    {
        return view('features.dashboard.employee.index');
    }
}
