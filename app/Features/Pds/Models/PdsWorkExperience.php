<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsWorkExperience extends Model
{
    protected $table = 'pds_work_experience';

    protected $fillable = [
        'pds_id',
        'employed_from',
        'employed_to',
        'position_title',
        'department',
        'salary',
        'salary_grade',
        'appointment_status',
        'is_government',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'employed_from' => 'date',
            'employed_to' => 'date',
            'is_government' => 'boolean',
            'salary' => 'decimal:2',
        ];
    }

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
