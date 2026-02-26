<?php

namespace App\Features\ActivityLogs\Http\Controllers\Admin;

use App\Features\ActivityLogs\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::select(['id', 'actor_user_id', 'action', 'subject_type', 'subject_id', 'ip_address', 'role', 'created_at'])
            ->with(['actor' => function ($q) {
                $q->select(['id', 'name', 'email']);
            }])
            ->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('actor')) {
            $query->where('actor_user_id', $request->input('actor'));
        }

        if ($request->filled('user')) {
            $search = trim((string) $request->input('user'));
            $query->whereHas('actor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs = $query->paginate(20)->appends($request->query());

        // Get unique actions for filter dropdown
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('features.activity-logs.admin.index', [
            'logs' => $logs,
            'actions' => $actions,
        ]);
    }

    public function export(Request $request)
    {
        $query = ActivityLog::select(['id', 'actor_user_id', 'action', 'subject_type', 'subject_id', 'ip_address', 'role', 'created_at', 'metadata'])
            ->with(['actor' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('actor')) {
            $query->where('actor_user_id', $request->input('actor'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $filename = 'activity_logs_'.now()->format('Y-m-d_H-i-s').'.csv';

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Headers
            fputcsv($handle, ['Timestamp', 'Actor', 'Role', 'Action', 'Subject Type', 'Subject ID', 'IP Address', 'Metadata']);

            $query->chunk(100, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->actor?->name ?? 'System',
                        $log->role ?? 'N/A',
                        $log->action,
                        $log->subject_type ?? 'N/A',
                        $log->subject_id ?? 'N/A',
                        $log->ip_address ?? 'N/A',
                        json_encode($log->metadata ?? []),
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

        return $response;
    }
}
