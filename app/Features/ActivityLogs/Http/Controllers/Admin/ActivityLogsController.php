<?php

namespace App\Features\ActivityLogs\Http\Controllers\Admin;

use App\Features\ActivityLogs\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(description) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(subject_type) like ?', ["%{$search}%"]);
            });
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $logs = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        return Inertia::render('Admin/ActivityLogs/Index', ['logs' => $logs]);
    }

    public function export(Request $request)
    {
        $rows = ActivityLog::orderByDesc('created_at')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'User', 'Action', 'Subject', 'Date']);
            foreach ($rows as $log) {
                fputcsv($out, [
                    $log->id,
                    $log->causer_id ?? '',
                    $log->description ?? '',
                    $log->subject_type ?? '',
                    optional($log->created_at)?->toDateString() ?? '',
                ]);
            }
            fclose($out);
        }, 'activity-logs.csv', ['Content-Type' => 'text/csv']);
    }
}
