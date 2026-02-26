<?php

namespace App\Features\Training\Models;

use App\Features\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    protected $fillable = [
        'employee_id',
        'employee_name',
        'title',
        'date_from',
        'date_to',
        'time_from',
        'time_to',
        'hours',
        'type',
        'provider',
        'category',
        'fee',
        'status',
        'participants',
    ];

    protected function casts(): array
    {
        return [
            'date_from' => 'date',
            'date_to' => 'date',
            'time_from' => 'datetime:H:i',
            'time_to' => 'datetime:H:i',
            'hours' => 'float',
            'fee' => 'float',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
