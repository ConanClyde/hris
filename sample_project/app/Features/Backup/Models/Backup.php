<?php

namespace App\Features\Backup\Models;

use App\Features\Users\Models\User;
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

    protected $casts = [
        'size_bytes' => 'integer',
        'completed_at' => 'datetime',
    ];

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
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        } else {
            return $bytes.' bytes';
        }
    }

    public function getFileExistsAttribute(): bool
    {
        return file_exists(storage_path('app/'.$this->path));
    }

    public function getSizeHumanAttribute(): string
    {
        return $this->size_formatted;
    }

    public function getCreatedAtIsoAttribute(): ?string
    {
        return $this->created_at?->toIso8601String();
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->notes;
    }
}
