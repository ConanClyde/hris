<?php

namespace App\Features\Notices\Models;

use App\Models\User;
use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notice extends Model
{
    use HasFactory;

    protected static function newFactory(): NoticeFactory
    {
        return NoticeFactory::new();
    }

    protected $fillable = [
        'title',
        'message',
        'type',
        'is_active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });
    }

    public function readers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notice_reads')
            ->withPivot('read_at');
    }
}
