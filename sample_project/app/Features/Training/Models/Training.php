<?php

namespace App\Features\Training\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'employee_id',
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

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
        'hours' => 'float',
        'fee' => 'float',
    ];
}
