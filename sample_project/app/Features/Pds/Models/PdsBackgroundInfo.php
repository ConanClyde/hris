<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsBackgroundInfo extends Model
{
    protected $table = 'pds_background_info';

    protected $fillable = [
        'pds_id',
        'answers',
        'details_34',
        'details_35',
        'details_36',
        'details_37',
        'details_38',
        'details_39',
        'details_40',
    ];

    // Important: 'answers' is a JSON column in migration
    protected $casts = [
        'answers' => 'array',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class);
    }
}
