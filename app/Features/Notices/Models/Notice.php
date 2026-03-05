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
        'target_roles',
        'target_division_id',
        'target_subdivision_id',
        'target_section_id',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'is_active' => 'boolean',
            'target_roles' => 'array',
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

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->whereNull('target_roles')
                ->orWhere('target_roles', '[]')
                ->orWhereRaw('JSON_CONTAINS(target_roles, ?)', [json_encode($user->role)]);
        })
            ->where(function ($q) use ($user) {
                $q->whereNull('target_division_id')
                    ->orWhere('target_division_id', $user->employee?->division_id);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('target_subdivision_id')
                    ->orWhere('target_subdivision_id', $user->employee?->subdivision_id);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('target_section_id')
                    ->orWhere('target_section_id', $user->employee?->section_id);
            });
    }

    public function markAsReadBy(User $user): void
    {
        if (! $this->isReadBy($user)) {
            $this->readers()->attach($user->id, ['read_at' => now()]);
        }
    }

    public function isReadBy(User $user): bool
    {
        return $this->readers()->where('user_id', $user->id)->exists();
    }

    public function readers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notice_reads')
            ->withPivot('read_at');
    }
}
