<?php

namespace App\Features\ActivityLogs\Http\Controllers\Admin;

use App\Features\ActivityLogs\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->applyFilters($this->baseQuery(), $request);
        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $logs = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery)
            ->through(function (ActivityLog $log): array {
                $metadata = $log->metadata ?? [];

                $description = is_array($metadata) ? ($metadata['description'] ?? null) : null;
                if (! is_string($description) || $description === '') {
                    $description = $log->subject_type
                        ? "{$log->action} {$log->subject_type}".($log->subject_id ? " #{$log->subject_id}" : '')
                        : $log->action;
                }

                $userId = $log->actor_user_id ? (int) $log->actor_user_id : 0;

                return [
                    'id' => (int) $log->id,
                    'user_id' => $userId,
                    'user_name' => $log->actor?->name ?? ($userId === 0 ? 'System' : null),
                    'avatar' => $log->actor?->avatar
                        ? asset('storage/'.$log->actor->avatar).'?v='.$log->actor->updated_at?->timestamp
                        : null,
                    'action' => $log->action,
                    'description' => $description,
                    'role' => $log->role,
                    'subject_type' => $log->subject_type,
                    'subject_id' => $log->subject_id,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'metadata' => is_array($metadata) ? $metadata : null,
                    'created_at' => $log->created_at?->toISOString() ?? '',
                ];
            });

        $action = (string) $request->input('action', 'all');

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => [
                'search' => $request->input('search'),
                'action' => $action,
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'actor_user_id' => $request->input('actor_user_id'),
                'ip' => $request->input('ip'),
                'role' => $request->input('role', 'all'),
                'subject_type' => $request->input('subject_type', 'all'),
                'subject_id' => $request->input('subject_id'),
            ],
        ]);
    }

    public function userIndex(Request $request)
    {
        $user = $request->user();
        $query = $this->applyFilters(
            $this->baseQuery()->where('actor_user_id', $user?->id),
            $request
        );
        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $logs = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery)
            ->through(function (ActivityLog $log): array {
                $metadata = $log->metadata ?? [];

                $description = is_array($metadata) ? ($metadata['description'] ?? null) : null;
                if (! is_string($description) || $description === '') {
                    $description = $log->subject_type
                        ? "{$log->action} {$log->subject_type}".($log->subject_id ? " #{$log->subject_id}" : '')
                        : $log->action;
                }

                $userId = $log->actor_user_id ? (int) $log->actor_user_id : 0;

                return [
                    'id' => (int) $log->id,
                    'user_id' => $userId,
                    'user_name' => $log->actor?->name ?? ($userId === 0 ? 'System' : null),
                    'avatar' => $log->actor?->avatar
                        ? asset('storage/'.$log->actor->avatar).'?v='.$log->actor->updated_at?->timestamp
                        : null,
                    'action' => $log->action,
                    'description' => $description,
                    'role' => $log->role,
                    'subject_type' => $log->subject_type,
                    'subject_id' => $log->subject_id,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'metadata' => is_array($metadata) ? $metadata : null,
                    'created_at' => $log->created_at?->toISOString() ?? '',
                ];
            });

        $routeName = (string) $request->route()?->getName();
        $action = (string) $request->input('action', 'all');

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => [
                'search' => $request->input('search'),
                'action' => $action,
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'actor_user_id' => $user?->id,
                'ip' => $request->input('ip'),
                'role' => $request->input('role', 'all'),
                'subject_type' => $request->input('subject_type', 'all'),
                'subject_id' => $request->input('subject_id'),
            ],
            'scoped' => true,
            'indexUrl' => $routeName !== '' ? route($routeName) : null,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $rows = $this->applyFilters($this->baseQuery(), $request)
            ->orderByDesc('created_at')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'ID',
                'User ID',
                'User Name',
                'Role',
                'Action',
                'Description',
                'Subject Type',
                'Subject ID',
                'IP Address',
                'User Agent',
                'Metadata',
                'Date & Time',
            ]);
            foreach ($rows as $log) {
                $metadata = $log->metadata ?? [];
                $description = is_array($metadata) ? ($metadata['description'] ?? null) : null;
                if (! is_string($description) || $description === '') {
                    $description = $log->subject_type
                        ? "{$log->action} {$log->subject_type}".($log->subject_id ? " #{$log->subject_id}" : '')
                        : $log->action;
                }

                $metadataValue = is_array($metadata) ? json_encode($metadata, JSON_UNESCAPED_UNICODE) : '';

                fputcsv($out, [
                    $log->id,
                    $log->actor_user_id ?? '',
                    $log->actor?->name ?? ($log->actor_user_id ? '' : 'System'),
                    $log->role ?? '',
                    $log->action ?? '',
                    $description,
                    $log->subject_type ?? '',
                    $log->subject_id ?? '',
                    $log->ip_address ?? '',
                    $log->user_agent ?? '',
                    $metadataValue,
                    optional($log->created_at)?->toDateTimeString() ?? '',
                ]);
            }
            fclose($out);
        }, 'activity-logs.csv', ['Content-Type' => 'text/csv']);
    }

    protected function applyFilters(Builder $query, Request $request): Builder
    {
        $action = (string) $request->input('action', 'all');
        if ($request->filled('action') && $action !== 'all') {
            $query->where('action', $action);
        }

        $role = (string) $request->input('role', 'all');
        if ($request->filled('role') && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($request->filled('actor_user_id')) {
            $actorId = (int) $request->input('actor_user_id');
            if ($actorId > 0) {
                $query->where('actor_user_id', $actorId);
            }
        }

        if ($request->filled('ip')) {
            $ip = trim((string) $request->input('ip'));
            if ($ip !== '') {
                $query->where('ip_address', 'like', "%{$ip}%");
            }
        }

        $subjectType = (string) $request->input('subject_type', 'all');
        if ($request->filled('subject_type') && $subjectType !== 'all') {
            $query->where('subject_type', $subjectType);
        }

        if ($request->filled('subject_id')) {
            $subjectId = (int) $request->input('subject_id');
            if ($subjectId > 0) {
                $query->where('subject_id', $subjectId);
            }
        }

        if ($request->filled('from')) {
            $from = trim((string) $request->input('from'));
            if ($from !== '') {
                $query->where('created_at', '>=', $from);
            }
        }
        if ($request->filled('to')) {
            $to = trim((string) $request->input('to'));
            if ($to !== '') {
                $query->where('created_at', '<=', $to);
            }
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->whereHas('actor', function (Builder $q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    protected function baseQuery(): Builder
    {
        return ActivityLog::query()
            ->select([
                'id',
                'actor_user_id',
                'role',
                'action',
                'subject_type',
                'subject_id',
                'metadata',
                'ip_address',
                'created_at',
            ])
            ->with([
                'actor' => function ($query): void {
                    $query->select(['id', 'name', 'email']);
                },
            ]);
    }
}
