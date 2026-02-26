<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsFamily extends Model
{
    protected $table = 'pds_families';

    protected $fillable = [
        'pds_id',
        'spouse_surname',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_name_extension',
        'spouse_occupation',
        'spouse_employer',
        'spouse_business_address',
        'spouse_telephone',
        'father_surname',
        'father_first_name',
        'father_middle_name',
        'father_name_extension',
        'mother_maiden_surname',
        'mother_maiden_first_name',
        'mother_maiden_middle_name',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
