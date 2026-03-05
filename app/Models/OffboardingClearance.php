<?php

namespace App\Models;

use App\Features\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OffboardingClearance extends Model
{
    protected $fillable = [
        'employee_id',
        'department',
        'title',
        'description',
        'status',
        'remarks',
        'cleared_at',
    ];

    protected function casts(): array
    {
        return [
            'cleared_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
