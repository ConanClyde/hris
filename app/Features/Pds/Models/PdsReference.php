<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsReference extends Model
{
    protected $table = 'pds_references';

    protected $fillable = [
        'pds_id',
        'reference_name',
        'reference_address',
        'reference_telno',
        'sort_order',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
