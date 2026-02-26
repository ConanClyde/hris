<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsCscEligibility extends Model
{
    protected $table = 'pds_csc_eligibility';

    protected $fillable = [
        'pds_id',
        'license_name',
        'rating',
        'date_of_examination',
        'place_of_examination',
        'license_no',
        'date_of_validity',
        'sort_order',
    ];

    protected $casts = [
        'date_of_examination' => 'date',
        'date_of_validity' => 'date',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class);
    }
}
