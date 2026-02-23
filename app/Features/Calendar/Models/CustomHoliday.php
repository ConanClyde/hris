<?php

namespace App\Features\Calendar\Models;

use Illuminate\Database\Eloquent\Model;

class CustomHoliday extends Model
{
    protected $fillable = [
        'title',
        'date',
        'category',
        'description',
        'is_recurring',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_recurring' => 'boolean',
        ];
    }
}
