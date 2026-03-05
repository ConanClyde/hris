<?php

namespace App\Features\AIChatbot\Repositories;

use App\Features\Notices\Models\Notice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class NoticeRepository
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Notice::query();

        if ($search = (string) ($filters['search'] ?? '')) {
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($filters['active_only'] ?? null) {
            $query->where('is_active', true);
        }

        if ($expiresAfter = (string) ($filters['expires_after'] ?? '')) {
            $query->whereDate('expires_at', '>=', $expiresAfter);
        }

        $query->orderByDesc('created_at');

        return $query->paginate($this->normalizePerPage($perPage));
    }

    private function normalizePerPage(int $perPage): int
    {
        $safe = max(1, min($perPage, 50));

        return $safe;
    }
}
