<?php

namespace App\Features\AIChatbot\Repositories;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class LeaveApplicationRepository
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = LeaveApplication::query();

        if ($search = (string) ($filters['search'] ?? '')) {
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        if ($status = (string) ($filters['status'] ?? '')) {
            $query->where('status', $status);
        }

        if ($employeeId = (string) ($filters['employee_id'] ?? '')) {
            $query->where('employee_id', $employeeId);
        }

        if ($dateFrom = (string) ($filters['date_from'] ?? '')) {
            $query->whereDate('date_from', '>=', $dateFrom);
        }

        if ($dateTo = (string) ($filters['date_to'] ?? '')) {
            $query->whereDate('date_to', '<=', $dateTo);
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
