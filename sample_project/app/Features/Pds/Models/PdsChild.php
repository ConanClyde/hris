<?php

namespace App\Features\Pds\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsChild extends Model
{
    use HasFactory;

    protected $fillable = [
        'pds_id',
        'name',
        'dob',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class);
    }
}
