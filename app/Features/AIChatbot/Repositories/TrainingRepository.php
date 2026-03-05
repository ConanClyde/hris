<?php

namespace App\Features\AIChatbot\Repositories;

use App\Features\Training\Models\Training;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TrainingRepository
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Training::query();

        if ($search = (string) ($filters['search'] ?? '')) {
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('provider', 'like', "%{$search}%");
            });
        }

        if ($status = (string) ($filters['status'] ?? '')) {
            $query->where('status', $status);
        }

        if ($employeeId = (string) ($filters['employee_id'] ?? '')) {
            $query->where('employee_id', $employeeId);
        }

        if ($employeeFk = (int) ($filters['employee_fk'] ?? 0)) {
            $query->where('employee_fk', $employeeFk);
        }

        if ($dateFrom = (string) ($filters['date_from'] ?? '')) {
            $query->whereDate('date_from', '>=', $dateFrom);
        }

        if ($dateTo = (string) ($filters['date_to'] ?? '')) {
            $query->whereDate('date_to', '<=', $dateTo);
        }

        $query->orderByDesc('date_from');

        return $query->paginate($this->normalizePerPage($perPage));
    }

    private function normalizePerPage(int $perPage): int
    {
        $safe = max(1, min($perPage, 50));

        return $safe;
    }
}
