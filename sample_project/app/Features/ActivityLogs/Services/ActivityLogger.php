<?php

namespace App\Features\ActivityLogs\Services;

use App\Features\ActivityLogs\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public function __construct(protected ?Request $request = null)
    {
        $this->request = $request ?? request();
    }

    public function log(
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $metadata = null
    ): void {
        ActivityLog::create([
            'actor_user_id' => Auth::id(),
            'role' => Auth::user()?->role,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'metadata' => $metadata,
            'ip_address' => $this->request?->ip(),
            'user_agent' => $this->request?->userAgent(),
        ]);
    }

    public function logLogin(): void
    {
        $this->log('login', 'user', Auth::id(), ['email' => Auth::user()?->email]);
    }

    public function logLogout(): void
    {
        $this->log('logout', 'user', Auth::id());
    }

    public function logCreate(string $model, int $id, array $data = []): void
    {
        $this->log('create', $model, $id, ['data' => $data]);
    }

    public function logUpdate(string $model, int $id, array $old = [], array $new = []): void
    {
        $this->log('update', $model, $id, ['old' => $old, 'new' => $new]);
    }

    public function logDelete(string $model, int $id): void
    {
        $this->log('delete', $model, $id);
    }

    public function logStatusChange(string $model, int $id, string $from, string $to): void
    {
        $this->log('status_change', $model, $id, ['from' => $from, 'to' => $to]);
    }

    public function logView(string $model, ?int $id = null): void
    {
        $this->log('view', $model, $id);
    }

    public function logExport(string $model): void
    {
        $this->log('export', $model, null, ['format' => 'csv']);
    }

    public function logBackup(string $action, ?string $filename = null): void
    {
        $this->log('backup_'.$action, 'backup', null, ['filename' => $filename]);
    }
}
