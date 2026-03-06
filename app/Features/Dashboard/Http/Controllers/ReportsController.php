<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $user = $request->user();
        $isHr = $user?->isHr();

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

        $userQuery = User::query();
        $employeeQuery = Employee::query();
        $leaveQuery = LeaveApplication::query();
        $trainingQuery = Training::query();

        if ($isHr) {
            $userQuery->where('role', '!=', UserRole::Admin->value);
            $employeeQuery->whereHas('user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
            $leaveQuery->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
            $trainingQuery->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        $currentYear = now()->year;

        $rangeStart = $fromDate ?: Carbon::create($currentYear, 1, 1)->startOfDay();
        $rangeEnd = $toDate ?: Carbon::create($currentYear, 12, 31)->endOfDay();

        $totalUsers = (int) $userQuery->count();
        $totalEmployees = (int) $employeeQuery->count();

        // Org breakdown (counts)
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

        $divisions = Division::query()->select(['id', 'name'])->orderBy('name')->get();
        $subdivisions = Subdivision::query()->select(['id', 'division_id', 'name'])->orderBy('name')->get();
        $sections = Section::query()->select(['id', 'division_id', 'subdivision_id', 'name'])->orderBy('name')->get();

        $orgBreakdown = $divisions->map(function ($div) use ($divisionCounts, $subdivisions, $subdivisionCounts, $sections, $sectionCounts) {
            $divId = (int) $div->id;
            $subs = $subdivisions
                ->where('division_id', $divId)
                ->map(function ($sub) use ($divId, $subdivisionCounts, $sections, $sectionCounts) {
                    $subId = (int) $sub->id;

                    $secs = $sections
                        ->where('division_id', $divId)
                        ->where('subdivision_id', $subId)
                        ->map(function ($sec) use ($sectionCounts) {
                            $secId = (int) $sec->id;

                            return [
                                'id' => $secId,
                                'name' => (string) $sec->name,
                                'count' => (int) ($sectionCounts[$secId] ?? 0),
                            ];
                        })
                        ->values()
                        ->all();

                    return [
                        'id' => $subId,
                        'name' => (string) $sub->name,
                        'count' => (int) ($subdivisionCounts[$subId] ?? 0),
                        'sections' => $secs,
                    ];
                })
                ->values()
                ->all();

            $directSections = $sections
                ->where('division_id', $divId)
                ->where('subdivision_id', null)
                ->map(function ($sec) use ($sectionCounts) {
                    $secId = (int) $sec->id;

                    return [
                        'id' => $secId,
                        'name' => (string) $sec->name,
                        'count' => (int) ($sectionCounts[$secId] ?? 0),
                    ];
                })
                ->values()
                ->all();

            return [
                'id' => $divId,
                'name' => (string) $div->name,
                'count' => (int) ($divisionCounts[$divId] ?? 0),
                'subdivisions' => $subs,
                'sections' => $directSections,
            ];
        })->values()->all();

        $unassigned = [
            'division' => (int) ($divisionCounts[''] ?? $divisionCounts[0] ?? 0),
            'subdivision' => (int) ($subdivisionCounts[''] ?? $subdivisionCounts[0] ?? 0),
            'section' => (int) ($sectionCounts[''] ?? $sectionCounts[0] ?? 0),
        ];

        // Sex distribution (authoritative: pds_personal.sex)
        $sexQuery = PdsPersonal::query()
            ->leftJoin('pds', 'pds_personal.pds_id', '=', 'pds.id')
            ->leftJoin('employees', 'pds.employee_id', '=', 'employees.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id');

        if ($isHr) {
            $sexQuery->where('users.role', '!=', UserRole::Admin->value);
        }

        $sexRows = $sexQuery
            ->selectRaw('LOWER(COALESCE(pds_personal.sex, "")) as sex, COUNT(*) as count')
            ->groupBy('sex')
            ->pluck('count', 'sex')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $maleCount = (int) ($sexRows['male'] ?? 0);
        $femaleCount = (int) ($sexRows['female'] ?? 0);
        $unknownSexCount = (int) array_sum($sexRows) - $maleCount - $femaleCount;
        $sexDistribution = [
            ['label' => 'Male', 'value' => $maleCount],
            ['label' => 'Female', 'value' => $femaleCount],
            ['label' => 'Unknown', 'value' => max(0, $unknownSexCount)],
        ];

        // Monthly hires (within range)
        $monthlyHires = Employee::query()
            ->whereNotNull('date_hired')
            ->whereBetween('date_hired', [$rangeStart, $rangeEnd])
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

        // Leave monthly trend (distinct employees on leave that month)
        $leaveMonthly = $leaveQuery
            ->clone()
            ->where('status', 'approved')
            ->whereDate('date_from', '<=', $rangeEnd->toDateString())
            ->whereDate('date_to', '>=', $rangeStart->toDateString())
            ->selectRaw('MONTH(date_from) as month, COUNT(DISTINCT employee_fk) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $leaveTrend = [];
        for ($m = 1; $m <= 12; $m++) {
            $leaveTrend[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'count' => (int) ($leaveMonthly[$m] ?? 0),
            ];
        }

        // Training monthly trend (distinct employees with training in that month)
        $trainingMonthly = $trainingQuery
            ->clone()
            ->whereDate('date_from', '<=', $rangeEnd->toDateString())
            ->whereDate('date_to', '>=', $rangeStart->toDateString())
            ->selectRaw('MONTH(date_from) as month, COUNT(DISTINCT employee_fk) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        $trainingTrend = [];
        for ($m = 1; $m <= 12; $m++) {
            $trainingTrend[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'count' => (int) ($trainingMonthly[$m] ?? 0),
            ];
        }

        // Yearly headcount trend (last 5 years)
        $headcountTrend = [];
        for ($y = $currentYear - 4; $y <= $currentYear; $y++) {
            $headcountTrend[] = [
                'year' => $y,
                'count' => (int) Employee::query()
                    ->whereNotNull('date_hired')
                    ->where('date_hired', '<=', "$y-12-31")
                    ->count(),
            ];
        }

        // Heatmaps (distinct employees per day)
        $leaveHeatRows = $leaveQuery
            ->clone()
            ->where('status', 'approved')
            ->whereDate('date_from', '<=', $rangeEnd->toDateString())
            ->whereDate('date_to', '>=', $rangeStart->toDateString())
            ->select(['employee_fk', 'date_from', 'date_to'])
            ->get();

        $leaveHeatmap = $this->buildDistinctEmployeeHeatmap($leaveHeatRows, $rangeStart, $rangeEnd);

        $trainingHeatRows = $trainingQuery
            ->clone()
            ->whereDate('date_from', '<=', $rangeEnd->toDateString())
            ->whereDate('date_to', '>=', $rangeStart->toDateString())
            ->select(['employee_fk', 'date_from', 'date_to'])
            ->get();

        $trainingHeatmap = $this->buildDistinctEmployeeHeatmap($trainingHeatRows, $rangeStart, $rangeEnd);

        $recommendations = $this->buildRecommendations(
            totalEmployees: $totalEmployees,
            unassigned: $unassigned,
            sexDistribution: $sexDistribution,
            leaveHeatmap: $leaveHeatmap,
            trainingHeatmap: $trainingHeatmap,
        );

        $summary = [
            'totalUsers' => $totalUsers,
            'totalEmployees' => $totalEmployees,
        ];

        $page = $isHr ? 'HR/Reports/Index' : 'Admin/Reports/Index';

        return Inertia::render($page, [
            'summary' => $summary,
            'hiresPerMonth' => $hiresPerMonth,
            'headcountTrend' => $headcountTrend,
            'currentYear' => $currentYear,
            'orgBreakdown' => $orgBreakdown,
            'unassigned' => $unassigned,
            'sexDistribution' => $sexDistribution,
            'leaveTrend' => $leaveTrend,
            'trainingTrend' => $trainingTrend,
            'leaveHeatmap' => $leaveHeatmap,
            'trainingHeatmap' => $trainingHeatmap,
            'recommendations' => $recommendations,
            'filters' => [
                'from' => $fromDate?->toDateString(),
                'to' => $toDate?->toDateString(),
            ],
        ]);
    }

    /**
     * @param  array{division:int,subdivision:int,section:int}  $unassigned
     * @param  array<int, array{label:string,value:int}>  $sexDistribution
     * @param  array<string,int>  $leaveHeatmap
     * @param  array<string,int>  $trainingHeatmap
     * @return array<int, array{title:string,detail:string,level:string}>
     */
    private function buildRecommendations(
        int $totalEmployees,
        array $unassigned,
        array $sexDistribution,
        array $leaveHeatmap,
        array $trainingHeatmap,
    ): array {
        $items = [];

        $unassignedTotal = ($unassigned['division'] ?? 0) + ($unassigned['subdivision'] ?? 0) + ($unassigned['section'] ?? 0);
        if ($totalEmployees > 0) {
            $ratio = $unassignedTotal / $totalEmployees;
            if ($ratio >= 0.1) {
                $items[] = [
                    'title' => 'Complete organizational assignments',
                    'detail' => 'A significant number of employees are missing division/subdivision/section assignments. Assigning org units improves reporting accuracy.',
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
                'detail' => 'A portion of employees have missing sex information in PDS personal data. Completing this field improves demographic reporting.',
                'level' => 'info',
            ];
        }

        $leavePeak = $leaveHeatmap !== [] ? max($leaveHeatmap) : 0;
        if ($leavePeak >= 5) {
            $items[] = [
                'title' => 'Review staffing during high leave periods',
                'detail' => 'Leave heatmap shows days with high concurrent absences. Consider planning coverage for peak periods.',
                'level' => 'info',
            ];
        }

        $trainingPeak = $trainingHeatmap !== [] ? max($trainingHeatmap) : 0;
        if ($trainingPeak >= 5) {
            $items[] = [
                'title' => 'Balance training schedules',
                'detail' => 'Training heatmap shows days with many employees in training. Consider distributing sessions to reduce operational impact.',
                'level' => 'info',
            ];
        }

        if ($items === []) {
            $items[] = [
                'title' => 'No major issues detected',
                'detail' => 'Org assignments and data completeness look good for the selected range.',
                'level' => 'success',
            ];
        }

        return $items;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, object>  $rows
     * @return array<string, int>
     */
    private function buildDistinctEmployeeHeatmap($rows, Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $employeesPerDay = [];

        $start = $rangeStart->copy()->startOfDay();
        $end = $rangeEnd->copy()->endOfDay();

        foreach ($rows as $row) {
            $employeeFk = (int) ($row->employee_fk ?? 0);
            if ($employeeFk <= 0) {
                continue;
            }

            $from = $row->date_from instanceof \DateTimeInterface ? Carbon::parse($row->date_from) : null;
            $to = $row->date_to instanceof \DateTimeInterface ? Carbon::parse($row->date_to) : null;

            if (! $from || ! $to) {
                continue;
            }

            if ($from->lessThan($start)) {
                $from = $start->copy();
            }
            if ($to->greaterThan($end)) {
                $to = $end->copy();
            }
            if ($to->lessThan($from)) {
                continue;
            }

            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                $key = $d->toDateString();
                $employeesPerDay[$key][$employeeFk] = true;
            }
        }

        $heatmap = [];
        foreach ($employeesPerDay as $date => $set) {
            $heatmap[$date] = count($set);
        }

        return $heatmap;
    }

    public function exportAdminAnalytics(Request $request)
    {
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

        $currentYear = now()->year;
        $rangeStart = $fromDate ?: Carbon::create($currentYear, 1, 1)->startOfDay();
        $rangeEnd = $toDate ?: Carbon::create($currentYear, 12, 31)->endOfDay();

        $monthlyHires = Employee::whereNotNull('date_hired')
            ->whereBetween('date_hired', [$rangeStart, $rangeEnd])
            ->selectRaw('MONTH(date_hired) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $hiresPerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $hiresPerMonth[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'count' => (int) ($monthlyHires[$m] ?? 0),
            ];
        }

        $headcountTrend = [];
        for ($y = $currentYear - 4; $y <= $currentYear; $y++) {
            $headcountTrend[] = [
                'year' => $y,
                'count' => (int) Employee::whereNotNull('date_hired')
                    ->where('date_hired', '<=', "$y-12-31")
                    ->count(),
            ];
        }

        $userCount = (int) User::query()->count();
        $employeeCount = (int) Employee::query()->count();

        $filename = 'admin-analytics-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($userCount, $employeeCount, $hiresPerMonth, $headcountTrend) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['section', 'key', 'value']);

            fputcsv($out, ['summary', 'users', $userCount]);
            fputcsv($out, ['summary', 'employees', $employeeCount]);

            foreach ($hiresPerMonth as $row) {
                fputcsv($out, ['hires_per_month', $row['month'], $row['count']]);
            }

            foreach ($headcountTrend as $row) {
                fputcsv($out, ['headcount_trend', (string) $row['year'], $row['count']]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
