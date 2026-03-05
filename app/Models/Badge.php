<?php

namespace App\Models;

use App\Features\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'bg_color',
        'criteria_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_badges')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }
}
