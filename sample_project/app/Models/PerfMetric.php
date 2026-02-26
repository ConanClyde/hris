<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfMetric extends Model
{
    protected $fillable = [
        'route',
        'user_id',
        'role',
        'fcp',
        'lcp',
        'cls',
        'ttfb',
        'dom_ready',
        'page_load',
        'nav_transition_ms',
        'user_agent',
        'ip',
        'budget_exceeded',
        'payload',
    ];

    protected $casts = [
        'cls' => 'decimal:3',
        'budget_exceeded' => 'boolean',
        'payload' => 'array',
    ];
}
