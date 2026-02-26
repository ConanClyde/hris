<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsChild extends Model
{
    protected $table = 'pds_children';

    protected $fillable = [
        'pds_id',
        'name',
        'dob',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class, 'pds_id');
    }
}
