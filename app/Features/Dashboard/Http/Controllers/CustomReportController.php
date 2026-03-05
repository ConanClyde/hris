<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CustomReportController extends Controller
{
    /**
     * Available data sources and their selectable columns.
     */
    private function sources(): array
    {
        return [
            'employees' => [
                'label' => 'Employees',
                'columns' => ['employee_id', 'first_name', 'last_name', 'middle_name', 'suffix', 'date_hired', 'sex'],
            ],
            'leave' => [
                'label' => 'Leave Applications',
                'columns' => ['employee_id', 'employee_name', 'type', 'date_from', 'date_to', 'total_days', 'status', 'reason'],
            ],
            'training' => [
                'label' => 'Training Records',
                'columns' => ['employee_id', 'employee_name', 'title', 'date_from', 'date_to', 'hours', 'type', 'provider', 'status'],
            ],
        ];
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('HR/Reports/CustomReport', [
            'sources' => $this->sources(),
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'source' => 'required|in:employees,leave,training',
            'columns' => 'required|array|min:1',
            'columns.*' => 'string',
        ]);

        $source = $request->input('source');
        $columns = $request->input('columns');
        $sourceDef = $this->sources()[$source];

        // Only allow valid columns
        $columns = array_values(array_intersect($columns, $sourceDef['columns']));
        if (empty($columns)) {
            return back()->withErrors(['columns' => 'No valid columns selected.']);
        }

        $query = match ($source) {
            'employees' => Employee::query()->select($columns),
            'leave' => LeaveApplication::query()
                ->when(Auth::user()?->isHr(), function ($q) {
                    $q->whereHas('employee.user', fn ($sq) => $sq->where('role', '!=', UserRole::Admin->value));
                })
                ->select($columns),
            'training' => Training::query()
                ->when(Auth::user()?->isHr(), function ($q) {
                    $q->whereHas('employee.user', fn ($sq) => $sq->where('role', '!=', UserRole::Admin->value));
                })
                ->select($columns),
        };

        $filename = "custom-report-{$source}-" . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);

            $query->chunkById(500, function ($rows) use ($out, $columns): void {
                foreach ($rows as $row) {
                    $line = [];
                    foreach ($columns as $col) {
                        $val = $row->{$col};
                        $line[] = $val instanceof \DateTimeInterface ? $val->toDateString() : (string) ($val ?? '');
                    }
                    fputcsv($out, $line);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
