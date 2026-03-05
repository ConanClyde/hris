<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Turnover & Retention analytics
        $currentYear = now()->year;

        // Monthly hires for the current year
        $monthlyHires = Employee::whereNotNull('date_hired')
            ->whereYear('date_hired', $currentYear)
            ->selectRaw('MONTH(date_hired) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Build full 12-month array
        $hiresPerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $hiresPerMonth[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'count' => $monthlyHires[$m] ?? 0,
            ];
        }

        // Department distribution
        $departmentDistribution = Employee::join('divisions', 'employees.division_id', '=', 'divisions.id')
            ->selectRaw('divisions.name as department, COUNT(employees.id) as count')
            ->groupBy('divisions.name')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => ['department' => $row->department, 'count' => (int) $row->count])
            ->toArray();

        // Yearly headcount trend (last 5 years)
        $headcountTrend = [];
        for ($y = $currentYear - 4; $y <= $currentYear; $y++) {
            $headcountTrend[] = [
                'year' => $y,
                'count' => Employee::whereNotNull('date_hired')
                    ->where('date_hired', '<=', "$y-12-31")
                    ->count(),
            ];
        }

        $page = $isHr ? 'HR/Reports/Index' : 'Admin/Reports/Index';

        return Inertia::render($page, [
            'summary' => $summary,
            'hiresPerMonth' => $hiresPerMonth,
            'departmentDistribution' => $departmentDistribution,
            'headcountTrend' => $headcountTrend,
            'currentYear' => $currentYear,
        ]);
    }
}
