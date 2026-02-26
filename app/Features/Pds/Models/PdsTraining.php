<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsTraining extends Model
{
    protected $table = 'pds_training';

    protected $fillable = [
        'pds_id',
        'title',
        'training_from',
        'training_to',
        'number_of_hours',
        'training_type',
        'sponsor',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'training_from' => 'date',
            'training_to' => 'date',
        ];
    }

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
