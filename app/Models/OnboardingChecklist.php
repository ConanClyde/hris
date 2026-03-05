<?php

namespace App\Models;

use App\Features\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingChecklist extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'description',
        'is_completed',
        'completed_at',
        'category',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
