<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_in_am',
        'time_out_am',
        'time_in_pm',
        'time_out_pm',
        'overtime_minutes',
        'undertime_minutes',
        'late_minutes',
        'status',
        'remarks',
        'ip_address',
        'device',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in_am' => 'datetime:H:i',
        'time_out_am' => 'datetime:H:i',
        'time_in_pm' => 'datetime:H:i',
        'time_out_pm' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
