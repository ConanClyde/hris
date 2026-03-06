<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
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

        // Lightweight analytics for dashboard
        $totalEmployees = (int) Employee::query()->count();
        $sexDistribution = $this->getSexDistribution(false);
        $unassigned = $this->getUnassignedCounts(false);
        $recommendations = $this->buildDashboardRecommendations(
            totalEmployees: $totalEmployees,
            unassigned: $unassigned,
            sexDistribution: $sexDistribution,
        );

        return Inertia::render('Admin/Dashboard', compact(
            'totalUsers',
            'pendingCount',
            'usersByRole',
            'usersByStatus',
            'totalEmployees',
            'sexDistribution',
            'unassigned',
            'recommendations'
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

        $today = Carbon::today()->toDateString();
        $outToday = LeaveApplication::with(['employee.user', 'employee.division'])
            ->where('status', 'approved')
            ->where('date_from', '<=', $today)
            ->where('date_to', '>=', $today)
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'employee_name' => $leave->employee->full_name ?? 'Unknown Employee',
                    'department' => $leave->employee->division->name ?? 'Unassigned',
                    'leave_type' => $leave->type ?? 'Leave',
                    'avatar' => $leave->employee->user->avatar ?? null,
                ];
            });

        // Lightweight analytics for dashboard
        $totalEmployees = (int) Employee::query()
            ->whereHas('user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            })->count();
        $sexDistribution = $this->getSexDistribution(true);
        $unassigned = $this->getUnassignedCounts(true);
        $miniLeaveTrend = $this->getMiniTrend('leave', true);
        $miniTrainingTrend = $this->getMiniTrend('training', true);
        $recommendations = $this->buildDashboardRecommendations(
            totalEmployees: $totalEmployees,
            unassigned: $unassigned,
            sexDistribution: $sexDistribution,
        );

        return Inertia::render('HR/Dashboard', [
            'totalUsers' => $totalUsers,
            'pendingLeaveCount' => $pendingLeaveCount,
            'pendingTrainingCount' => $pendingTrainingCount,
            'pdsPendingCount' => $pdsPendingCount,
            'outToday' => $outToday,
            'totalEmployees' => $totalEmployees,
            'sexDistribution' => $sexDistribution,
            'unassigned' => $unassigned,
            'miniLeaveTrend' => $miniLeaveTrend,
            'miniTrainingTrend' => $miniTrainingTrend,
            'recommendations' => $recommendations,
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
        $badges = [];

        if ($employeeId !== null) {
            $leaveCount = LeaveApplication::where('employee_fk', $employeeId)->count();
            $trainingCount = Training::where('employee_fk', $employeeId)->count();
            $pds = Pds::where('employee_id', $employeeId)->first();
            $pdsStatus = $pds?->status ?? null;
            $badges = $user->employee->badges ?? [];
        }

        $today = Carbon::today()->toDateString();
        $outToday = LeaveApplication::with(['employee.user', 'employee.division'])
            ->where('status', 'approved')
            ->where('date_from', '<=', $today)
            ->where('date_to', '>=', $today)
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'employee_name' => $leave->employee->full_name ?? 'Unknown Employee',
                    'department' => $leave->employee->division->name ?? 'Unassigned',
                    'leave_type' => $leave->type ?? 'Leave',
                    'avatar' => $leave->employee->user->avatar ?? null,
                ];
            });

        return Inertia::render('Employee/Dashboard', [
            'leaveCount' => $leaveCount,
            'trainingCount' => $trainingCount,
            'pdsStatus' => $pdsStatus,
            'badges' => $badges,
            'outToday' => $outToday,
            'user' => $user ? ['first_name' => $user->first_name ?? 'User'] : null,
        ]);
    }

    /**
     * Get sex distribution for dashboard (lightweight).
     *
     * @return array<int, array{label: string, value: int}>
     */
    private function getSexDistribution(bool $excludeAdmins): array
    {
        $query = PdsPersonal::query()
            ->leftJoin('pds', 'pds_personal.pds_id', '=', 'pds.id')
            ->leftJoin('employees', 'pds.employee_id', '=', 'employees.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id');

        if ($excludeAdmins) {
            $query->where('users.role', '!=', UserRole::Admin->value);
        }

        $sexRows = $query
            ->selectRaw('LOWER(COALESCE(pds_personal.sex, "")) as sex, COUNT(*) as count')
            ->groupBy('sex')
            ->pluck('count', 'sex')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $maleCount = (int) ($sexRows['male'] ?? 0);
        $femaleCount = (int) ($sexRows['female'] ?? 0);
        $unknownSexCount = (int) array_sum($sexRows) - $maleCount - $femaleCount;

        return [
            ['label' => 'Male', 'value' => $maleCount],
            ['label' => 'Female', 'value' => $femaleCount],
            ['label' => 'Unknown', 'value' => max(0, $unknownSexCount)],
        ];
    }

    /**
     * Get unassigned org counts for dashboard.
     *
     * @return array{division: int, subdivision: int, section: int}
     */
    private function getUnassignedCounts(bool $excludeAdmins): array
    {
        $employeeQuery = Employee::query();

        if ($excludeAdmins) {
            $employeeQuery->whereHas('user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        $divisionCounts = $employeeQuery
            ->clone()
            ->selectRaw('division_id, COUNT(*) as count')
            ->groupBy('division_id')
            ->pluck('count', 'division_id')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $subdivisionCounts = $employeeQuery
            ->clone()
            ->selectRaw('subdivision_id, COUNT(*) as count')
            ->groupBy('subdivision_id')
            ->pluck('count', 'subdivision_id')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $sectionCounts = $employeeQuery
            ->clone()
            ->selectRaw('section_id, COUNT(*) as count')
            ->groupBy('section_id')
            ->pluck('count', 'section_id')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        return [
            'division' => (int) ($divisionCounts[''] ?? $divisionCounts[0] ?? 0),
            'subdivision' => (int) ($subdivisionCounts[''] ?? $subdivisionCounts[0] ?? 0),
            'section' => (int) ($sectionCounts[''] ?? $sectionCounts[0] ?? 0),
        ];
    }

    /**
     * Get mini trend for last 6 months (dashboard only).
     *
     * @return array<int, array{month: string, count: int}>
     */
    private function getMiniTrend(string $type, bool $excludeAdmins): array
    {
        $endDate = Carbon::today()->endOfMonth();
        $startDate = Carbon::today()->subMonths(5)->startOfMonth();

        if ($type === 'leave') {
            $query = LeaveApplication::query()
                ->where('status', 'approved')
                ->whereDate('date_from', '<=', $endDate->toDateString())
                ->whereDate('date_to', '>=', $startDate->toDateString());

            if ($excludeAdmins) {
                $query->whereHas('employee.user', function ($q) {
                    $q->where('role', '!=', UserRole::Admin->value);
                });
            }

            $data = $query
                ->selectRaw('MONTH(date_from) as month, COUNT(DISTINCT employee_fk) as count')
                ->groupBy('month')
                ->pluck('count', 'month')
                ->map(fn ($v) => (int) $v)
                ->toArray();
        } else {
            $query = Training::query()
                ->whereDate('date_from', '<=', $endDate->toDateString())
                ->whereDate('date_to', '>=', $startDate->toDateString());

            if ($excludeAdmins) {
                $query->whereHas('employee.user', function ($q) {
                    $q->where('role', '!=', UserRole::Admin->value);
                });
            }

            $data = $query
                ->selectRaw('MONTH(date_from) as month, COUNT(DISTINCT employee_fk) as count')
                ->groupBy('month')
                ->pluck('count', 'month')
                ->map(fn ($v) => (int) $v)
                ->toArray();
        }

        $result = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::today()->subMonths($i);
            $monthNum = (int) $date->format('n');
            $result[] = [
                'month' => $date->format('M'),
                'count' => (int) ($data[$monthNum] ?? 0),
            ];
        }

        return $result;
    }

    /**
     * Build lightweight recommendations for dashboard.
     *
     * @param  array{division: int, subdivision: int, section: int}  $unassigned
     * @param  array<int, array{label: string, value: int}>  $sexDistribution
     * @return array<int, array{title: string, detail: string, level: string}>
     */
    private function buildDashboardRecommendations(
        int $totalEmployees,
        array $unassigned,
        array $sexDistribution,
    ): array {
        $items = [];

        $unassignedTotal = ($unassigned['division'] ?? 0) + ($unassigned['subdivision'] ?? 0) + ($unassigned['section'] ?? 0);
        if ($totalEmployees > 0) {
            $ratio = $unassignedTotal / $totalEmployees;
            if ($ratio >= 0.1) {
                $items[] = [
                    'title' => 'Complete organizational assignments',
                    'detail' => 'A significant number of employees are missing division/subdivision/section assignments.',
                    'level' => 'warning',
                ];
            }
        }

        $unknownSex = 0;
        foreach ($sexDistribution as $row) {
            if (strtolower((string) ($row['label'] ?? '')) === 'unknown') {
                $unknownSex = (int) ($row['value'] ?? 0);
            }
        }
        if ($totalEmployees > 0 && ($unknownSex / $totalEmployees) >= 0.05) {
            $items[] = [
                'title' => 'Improve sex data completeness',
                'detail' => 'A portion of employees have missing sex information in PDS personal data.',
                'level' => 'info',
            ];
        }

        if ($items === []) {
            $items[] = [
                'title' => 'No major issues detected',
                'detail' => 'Org assignments and data completeness look good.',
                'level' => 'success',
            ];
        }

        return array_slice($items, 0, 2);
    }
}
