<?php

namespace App\Features\Calendar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date', // Y-m-d
        'category', // e.g., 'regular', 'special', 'local'
        'description',
        'is_recurring', // boolean
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];
}
