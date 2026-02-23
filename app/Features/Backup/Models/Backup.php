<?php

namespace App\Features\Backup\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'disk',
        'path',
        'size_bytes',
        'checksum',
        'created_by_user_id',
        'status',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size_bytes;

        if ($bytes === null) {
            return '—';
        }

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }
}
