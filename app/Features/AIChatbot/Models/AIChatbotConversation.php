<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AIChatbotConversation extends Model
{
    protected $table = 'ai_chatbot_conversations';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'status',
        'last_message_at',
        'metadata',
        'summary',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function hasSummary(): bool
    {
        return is_string($this->summary) && $this->summary !== '';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AIChatbotMessage::class, 'conversation_id')
            ->orderBy('sequence_number');
    }

    public function latestMessages(int $limit = 20): HasMany
    {
        return $this->hasMany(AIChatbotMessage::class, 'conversation_id')
            ->orderByDesc('sequence_number')
            ->limit($limit);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    public function restore(): void
    {
        $this->update(['status' => 'active']);
    }

    public function softDelete(): void
    {
        $this->update(['status' => 'deleted']);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function updateLastMessage(): void
    {
        $this->update(['last_message_at' => now()]);
    }

    public function getMessageCount(): int
    {
        return $this->messages()->count();
    }

    public function generateTitle(string $firstMessage): void
    {
        $title = mb_substr($firstMessage, 0, 50);
        if (mb_strlen($firstMessage) > 50) {
            $title .= '...';
        }
        $this->update(['title' => $title]);
    }
}
