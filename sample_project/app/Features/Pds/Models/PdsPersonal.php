<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsPersonal extends Model
{
    protected $table = 'pds_personal';

    protected $fillable = [
        'pds_id',
        'surname',
        'first_name',
        'middle_name',
        'name_extension',
        'dob',
        'place_of_birth',
        'sex',
        'civil_status',
        'height',
        'weight',
        'blood_type',
        'citizenship_type',
        'citizenship_nature',
        'citizenship_country',
        'phone',
        'mobile',
        'email',
        'cs_id',
        'agency_employee_no',
        'gsis',
        'pag_ibig',
        'philhealth',
        'sss',
        'tin',
        'residential_address',
        'permanent_address',
        'gov_id_type',
        'gov_id_no',
        'gov_id_issuance',
    ];

    protected $casts = [
        'dob' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'residential_address' => 'array',
        'permanent_address' => 'array',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? ' '.$this->middle_name : '';
        $ext = $this->name_extension ? ' '.$this->name_extension : '';

        return trim($this->first_name.$middle.' '.$this->surname.$ext);
    }
}
