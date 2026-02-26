<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsEducation extends Model
{
    use HasFactory;

    protected $table = 'pds_education'; // Explicit table name just in case

    protected $fillable = [
        'pds_id',
        'level',
        'school_name',
        'degree_course',
        'period_from',
        'period_to',
        'highest_level',
        'year_graduated',
        'scholarship_honors',
        'awards',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class);
    }
}
