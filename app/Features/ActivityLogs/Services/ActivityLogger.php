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
        ?int $actorUserId = null,
        ?string $role = null,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $metadata = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): ActivityLog {
        $user = Auth::user();

        return ActivityLog::create([
            'actor_user_id' => $actorUserId ?? Auth::id(),
            'role' => $role ?? $user?->role,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'metadata' => $metadata,
            'ip_address' => $ipAddress ?? $this->request?->ip(),
            'user_agent' => $userAgent ?? $this->request?->userAgent(),
        ]);
    }
}
