<?php

namespace App\Models;

use App\Features\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeBadge extends Pivot
{
    protected $table = 'employee_badges';

    protected $fillable = [
        'employee_id',
        'badge_id',
        'awarded_at',
    ];

    protected function casts(): array
    {
        return [
            'awarded_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}
