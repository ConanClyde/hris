<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsOtherInfo extends Model
{
    protected $table = 'pds_other_info';

    protected $fillable = [
        'pds_id',
        'skills',
        'recognition',
        'membership',
        'sort_order',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
