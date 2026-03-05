<?php

namespace App\Features\AIChatbot\Repositories;

use App\Features\ActivityLogs\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ActivityLogRepository
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = ActivityLog::query()
            ->with('actor')
            ->latest('created_at');

        if ($action = (string) ($filters['action'] ?? '')) {
            $query->where('action', 'like', "%{$action}%");
        }

        if ($subjectType = (string) ($filters['subject_type'] ?? '')) {
            $query->where('subject_type', 'like', "%{$subjectType}%");
        }

        if ($actorId = (string) ($filters['actor_id'] ?? '')) {
            $query->where('actor_id', $actorId);
        }

        if ($dateFrom = $filters['date_from'] ?? null) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $filters['date_to'] ?? null) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query->paginate(max(1, min($perPage, 50)));
    }

    /**
     * @return Collection<int, ActivityLog>
     */
    public function recent(int $limit = 5): Collection
    {
        return ActivityLog::with('actor')
            ->latest('created_at')
            ->limit(max(1, min($limit, 100)))
            ->get();
    }
}
