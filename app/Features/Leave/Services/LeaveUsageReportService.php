<?php

declare(strict_types=1);

namespace App\Features\Leave\Services;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LeaveUsageReportService
{
    /**
     * Generate monthly leave usage report.
     *
     * @return array{
     *     period: string,
     *     total_applications: int,
     *     total_days: float,
     *     by_type: array<string, array{count: int, days: float}>,
     *     by_status: array<string, int>,
     *     by_department: array<string, array{count: int, days: float}>,
     *     top_leave_takers: array<int, array{name: string, days: float, count: int}>
     * }
     */
    public function generateMonthlyReport(int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $applications = LeaveApplication::query()
            ->whereBetween('date_from', [$startDate, $endDate])
            ->with('employee')
            ->get();

        $byType = $this->aggregateByType($applications);
        $byStatus = $applications->groupBy('status')->map->count()->toArray();
        $byDepartment = $this->aggregateByDepartment($applications);
        $topLeaveTakers = $this->getTopLeaveTakers($applications);

        return [
            'period' => $startDate->format('F Y'),
            'total_applications' => $applications->count(),
            'total_days' => (float) $applications->sum('total_days'),
            'by_type' => $byType,
            'by_status' => $byStatus,
            'by_department' => $byDepartment,
            'top_leave_takers' => $topLeaveTakers,
        ];
    }

    /**
     * Generate yearly leave usage report.
     *
     * @return array{
     *     year: int,
     *     total_applications: int,
     *     total_days: float,
     *     by_type: array<string, array{count: int, days: float}>,
     *     by_status: array<string, int>,
     *     monthly_trend: array<int, array{count: int, days: float}>,
     *     by_department: array<string, array{count: int, days: float}>
     * }
     */
    public function generateYearlyReport(int $year): array
    {
        $applications = LeaveApplication::query()
            ->whereYear('date_from', $year)
            ->with('employee')
            ->get();

        $byType = $this->aggregateByType($applications);
        $byStatus = $applications->groupBy('status')->map->count()->toArray();
        $monthlyTrend = $this->aggregateMonthlyTrend($applications, $year);
        $byDepartment = $this->aggregateByDepartment($applications);

        return [
            'year' => $year,
            'total_applications' => $applications->count(),
            'total_days' => (float) $applications->sum('total_days'),
            'by_type' => $byType,
            'by_status' => $byStatus,
            'monthly_trend' => $monthlyTrend,
            'by_department' => $byDepartment,
        ];
    }

    /**
     * Export leave report to CSV.
     *
     * @param  int|null  $employeeId  Filter by employee (null for all)
     * @param  string|null  $dateFrom  Y-m-d format
     * @param  string|null  $dateTo  Y-m-d format
     * @return array{headers: array<string>, rows: array<array>}
     */
    public function exportReport(?int $employeeId = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $headers = [
            'Employee ID',
            'Employee Name',
            'Leave Type',
            'Date From',
            'Date To',
            'Total Days',
            'Status',
            'Reason',
            'Submitted Date',
        ];

        $query = LeaveApplication::query()
            ->with('employee');

        if ($employeeId !== null) {
            $query->where('employee_fk', $employeeId);
        }

        if ($dateFrom !== null && $dateTo !== null) {
            $query->whereBetween('date_from', [$dateFrom, $dateTo]);
        }

        $rows = $query->orderBy('date_from', 'desc')
            ->get()
            ->map(fn ($leave) => [
                $leave->employee_id,
                $leave->employee_name,
                $leave->type,
                $leave->date_from?->format('Y-m-d'),
                $leave->date_to?->format('Y-m-d'),
                $leave->total_days,
                $leave->status,
                $leave->reason,
                $leave->created_at?->format('Y-m-d H:i:s'),
            ])
            ->toArray();

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    /**
     * Aggregate applications by leave type.
     *
     * @param  Collection<int, LeaveApplication>  $applications
     * @return array<string, array{count: int, days: float}>
     */
    private function aggregateByType(Collection $applications): array
    {
        return $applications->groupBy('type')
            ->map(fn ($group) => [
                'count' => $group->count(),
                'days' => (float) $group->sum('total_days'),
            ])
            ->toArray();
    }

    /**
     * Aggregate applications by department.
     *
     * @param  Collection<int, LeaveApplication>  $applications
     * @return array<string, array{count: int, days: float}>
     */
    private function aggregateByDepartment(Collection $applications): array
    {
        return $applications->groupBy(fn ($app) => $app->employee?->division?->name ?? 'Unassigned')
            ->map(fn ($group) => [
                'count' => $group->count(),
                'days' => (float) $group->sum('total_days'),
            ])
            ->toArray();
    }

    /**
     * Aggregate monthly trend for yearly report.
     *
     * @param  Collection<int, LeaveApplication>  $applications
     * @return array<int, array{count: int, days: float}>
     */
    private function aggregateMonthlyTrend(Collection $applications, int $year): array
    {
        $trend = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthApps = $applications->filter(fn ($app) => $app->date_from?->month === $month);
            $trend[$month] = [
                'count' => $monthApps->count(),
                'days' => (float) $monthApps->sum('total_days'),
            ];
        }

        return $trend;
    }

    /**
     * Get top leave takers.
     *
     * @param  Collection<int, LeaveApplication>  $applications
     * @param  int  $limit  Number of top employees to return
     * @return array<int, array{name: string, days: float, count: int}>
     */
    private function getTopLeaveTakers(Collection $applications, int $limit = 10): array
    {
        return $applications->groupBy('employee_fk')
            ->map(fn ($group) => [
                'name' => $group->first()->employee_name ?? 'Unknown',
                'days' => (float) $group->sum('total_days'),
                'count' => $group->count(),
            ])
            ->sortByDesc('days')
            ->take($limit)
            ->values()
            ->toArray();
    }
}
