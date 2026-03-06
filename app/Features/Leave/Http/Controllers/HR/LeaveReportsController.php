<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaveReportsController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) ($request->integer('year') ?: now()->year);

        $from = $request->input('from');
        $to = $request->input('to');

        $fromDate = null;
        $toDate = null;
        try {
            $fromDate = $from ? Carbon::parse((string) $from)->startOfDay() : null;
        } catch (\Throwable) {
            $fromDate = null;
        }
        try {
            $toDate = $to ? Carbon::parse((string) $to)->endOfDay() : null;
        } catch (\Throwable) {
            $toDate = null;
        }

        $rangeStart = $fromDate ?: now()->setYear($year)->startOfYear()->startOfDay();
        $rangeEnd = $toDate ?: now()->setYear($year)->endOfYear()->endOfDay();

        $creditsQuery = LeaveCredit::query()->with(['employee.user']);
        $appsQuery = LeaveApplication::query();

        if (Auth::user()?->isHr()) {
            $creditsQuery->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });

            $appsQuery
                ->leftJoin('employees', 'leave_applications.employee_fk', '=', 'employees.id')
                ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                ->where('users.role', '!=', UserRole::Admin->value);
        }

        $usageRows = (clone $appsQuery)
            ->where('leave_applications.status', 'approved')
            ->whereBetween('leave_applications.date_from', [$rangeStart, $rangeEnd])
            ->selectRaw('leave_applications.employee_fk, leave_applications.employee_id, leave_applications.employee_name, leave_applications.type, SUM(leave_applications.total_days) as used_days')
            ->groupBy('leave_applications.employee_fk', 'leave_applications.employee_id', 'leave_applications.employee_name', 'leave_applications.type')
            ->get()
            ->map(function ($row) {
                return [
                    'employee_fk' => $row->employee_fk,
                    'employee_id' => $row->employee_id,
                    'employee_name' => $row->employee_name,
                    'type' => $row->type,
                    'used_days' => (float) $row->used_days,
                ];
            })
            ->values()
            ->all();

        $wellnessUsageByEmployee = collect($usageRows)
            ->where('type', LeaveType::Wellness->value)
            ->groupBy('employee_fk')
            ->map(fn ($rows) => (float) collect($rows)->sum('used_days'))
            ->all();

        $forcedUsageByEmployee = collect($usageRows)
            ->where('type', LeaveType::MandatoryForced->value)
            ->groupBy('employee_fk')
            ->map(fn ($rows) => (float) collect($rows)->sum('used_days'))
            ->all();

        $vlBalanceByEmployee = $creditsQuery
            ->clone()
            ->where('leave_type', LeaveType::Vacation->value)
            ->get()
            ->keyBy('employee_id')
            ->map(fn ($c) => (float) $c->balance)
            ->all();

        $employees = $creditsQuery
            ->clone()
            ->whereIn('leave_type', [LeaveType::Vacation->value, LeaveType::Wellness->value])
            ->get()
            ->groupBy('employee_id')
            ->map(function ($credits) use ($vlBalanceByEmployee, $wellnessUsageByEmployee, $forcedUsageByEmployee) {
                $first = $credits->first();
                $employee = $first?->employee;
                $employeeId = (int) ($first?->employee_id ?? 0);

                $wellnessBalance = (float) optional($credits->firstWhere('leave_type', LeaveType::Wellness->value))->balance;

                return [
                    'employee_id' => $employeeId,
                    'employee_name' => $employee?->full_name ?? ('Employee #'.$employeeId),
                    'vl_balance' => (float) ($vlBalanceByEmployee[$employeeId] ?? 0),
                    'forced_used' => (float) ($forcedUsageByEmployee[$employeeId] ?? 0),
                    'wellness_used' => (float) ($wellnessUsageByEmployee[$employeeId] ?? 0),
                    'wellness_balance' => $wellnessBalance,
                ];
            })
            ->values()
            ->sortBy('employee_name')
            ->values()
            ->all();

        $forcedCompliance = array_values(array_map(function ($e) {
            $needsForced = $e['vl_balance'] >= 10.0;
            $isCompliant = ! $needsForced || $e['forced_used'] >= 5.0;

            return [
                'employee_id' => $e['employee_id'],
                'employee_name' => $e['employee_name'],
                'vl_balance' => $e['vl_balance'],
                'forced_used' => $e['forced_used'],
                'requires_forced' => $needsForced,
                'compliant' => $isCompliant,
            ];
        }, $employees));

        // Build daily leave-count heatmap data for the year (distinct employees on leave per day)
        $heatmapQuery = LeaveApplication::query()
            ->where('leave_applications.status', 'approved')
            ->whereDate('leave_applications.date_from', '<=', $rangeEnd->toDateString())
            ->whereDate('leave_applications.date_to', '>=', $rangeStart->toDateString());

        if (Auth::user()?->isHr()) {
            $heatmapQuery
                ->leftJoin('employees', 'leave_applications.employee_fk', '=', 'employees.id')
                ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                ->where('users.role', '!=', UserRole::Admin->value);
        }

        $heatmapRows = $heatmapQuery
            ->select([
                'leave_applications.employee_fk',
                'leave_applications.date_from',
                'leave_applications.date_to',
            ])
            ->get();

        $yearStart = $rangeStart->copy()->startOfDay();
        $yearEnd = $rangeEnd->copy()->endOfDay();

        $employeesPerDay = [];
        foreach ($heatmapRows as $row) {
            $employeeFk = (int) ($row->employee_fk ?? 0);
            if ($employeeFk <= 0) {
                continue;
            }

            $from = optional($row->date_from)?->copy();
            $to = optional($row->date_to)?->copy();
            if (! $from || ! $to) {
                continue;
            }

            if ($from->lessThan($yearStart)) {
                $from = $yearStart->copy();
            }
            if ($to->greaterThan($yearEnd)) {
                $to = $yearEnd->copy();
            }

            if ($to->lessThan($from)) {
                continue;
            }

            foreach (CarbonPeriod::create($from, $to) as $date) {
                $key = $date->toDateString();
                $employeesPerDay[$key][$employeeFk] = true;
            }
        }

        $heatmapRaw = [];
        foreach ($employeesPerDay as $date => $employeesSet) {
            $heatmapRaw[$date] = count($employeesSet);
        }

        return Inertia::render('HR/Reports/Leave', [
            'year' => $year,
            'types' => array_column(LeaveType::cases(), 'value'),
            'usageRows' => $usageRows,
            'employees' => $employees,
            'forcedCompliance' => $forcedCompliance,
            'heatmapData' => $heatmapRaw,
            'filters' => [
                'from' => $fromDate?->toDateString(),
                'to' => $toDate?->toDateString(),
            ],
        ]);
    }

    public function export(Request $request)
    {
        $year = (int) ($request->integer('year') ?: now()->year);

        $from = $request->input('from');
        $to = $request->input('to');

        $fromDate = null;
        $toDate = null;
        try {
            $fromDate = $from ? Carbon::parse((string) $from)->startOfDay() : null;
        } catch (\Throwable) {
            $fromDate = null;
        }
        try {
            $toDate = $to ? Carbon::parse((string) $to)->endOfDay() : null;
        } catch (\Throwable) {
            $toDate = null;
        }

        $rangeStart = $fromDate ?: now()->setYear($year)->startOfYear()->startOfDay();
        $rangeEnd = $toDate ?: now()->setYear($year)->endOfYear()->endOfDay();

        $query = LeaveApplication::query()
            ->where('leave_applications.status', 'approved')
            ->whereDate('leave_applications.date_from', '<=', $rangeEnd->toDateString())
            ->whereDate('leave_applications.date_to', '>=', $rangeStart->toDateString());

        if (Auth::user()?->isHr()) {
            $query
                ->leftJoin('employees', 'leave_applications.employee_fk', '=', 'employees.id')
                ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                ->where('users.role', '!=', UserRole::Admin->value);
        }

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee ID', 'Employee Name', 'Type', 'Start Date', 'Total Days', 'Status']);

            $query->orderBy('employee_fk')->chunkById(500, function ($rows) use ($out): void {
                foreach ($rows as $leave) {
                    fputcsv($out, [
                        $leave->employee_id ?? '',
                        $leave->employee_name ?? '',
                        $leave->type ?? '',
                        optional($leave->date_from)?->toDateString() ?? '',
                        $leave->total_days ?? '',
                        $leave->status ?? '',
                    ]);
                }
            }, 'leave_applications.id');

            fclose($out);
        }, "leave-report-{$year}.csv", ['Content-Type' => 'text/csv']);
    }
}
