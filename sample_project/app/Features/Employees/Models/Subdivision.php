<?php

namespace App\Features\Employees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subdivision extends Model
{
    protected $fillable = [
        'division_id',
        'name',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
