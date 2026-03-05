<?php

namespace App\Features\AIChatbot\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query();

        if ($search = (string) ($filters['search'] ?? '')) {
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($role = (string) ($filters['role'] ?? '')) {
            $query->where('role', $role);
        }

        if ($excludeRole = (string) ($filters['role_exclude'] ?? '')) {
            $query->where('role', '!=', $excludeRole);
        }

        if ($status = (string) ($filters['status'] ?? '')) {
            $query->where('status', $status);
        }

        if ($filters['is_active'] ?? null) {
            $query->where('is_active', (bool) $filters['is_active']);
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
