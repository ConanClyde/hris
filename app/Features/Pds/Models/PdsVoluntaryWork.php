<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsVoluntaryWork extends Model
{
    protected $table = 'pds_voluntary_work';

    protected $fillable = [
        'pds_id',
        'org_name_address',
        'volunteer_from',
        'volunteer_to',
        'number_of_hours',
        'nature_of_work',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'volunteer_from' => 'date',
            'volunteer_to' => 'date',
        ];
    }

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
