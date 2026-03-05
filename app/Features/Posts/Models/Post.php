<?php

namespace App\Features\Posts\Models;

use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image_path',
        'expires_at',
        'created_by',
        'role_scope',
        'is_pinned',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'bool',
            'is_published' => 'bool',
            'expires_at' => 'datetime',
        ];
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }
}
