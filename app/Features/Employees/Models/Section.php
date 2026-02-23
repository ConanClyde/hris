<?php

namespace App\Features\Employees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'division_id',
        'subdivision_id',
        'name',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
