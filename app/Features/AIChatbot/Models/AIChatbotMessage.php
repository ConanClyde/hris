<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AIChatbotMessage extends Model
{
    protected $table = 'ai_chatbot_messages';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'conversation_id',
        'role',
        'content_encrypted',
        'content_hash',
        'sources_encrypted',
        'tool_used',
        'tool_data_encrypted',
        'sequence_number',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AIChatbotConversation::class, 'conversation_id');
    }

    public function setContentAttribute(string $value): void
    {
        $this->content_encrypted = Crypt::encryptString($value);
        $this->content_hash = hash('sha256', $value);
    }

    public function getContentAttribute(): string
    {
        if (empty($this->content_encrypted)) {
            return '';
        }
        try {
            return Crypt::decryptString($this->content_encrypted);
        } catch (\Exception $e) {
            return '[Unable to decrypt message]';
        }
    }

    public function setSourcesAttribute(?array $value): void
    {
        if ($value === null || empty($value)) {
            $this->sources_encrypted = null;

            return;
        }
        $this->sources_encrypted = Crypt::encryptString(json_encode($value));
    }

    public function getSourcesAttribute(): ?array
    {
        if (empty($this->sources_encrypted)) {
            return null;
        }
        try {
            $decrypted = Crypt::decryptString($this->sources_encrypted);

            return json_decode($decrypted, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setToolDataAttribute(?array $value): void
    {
        if ($value === null || empty($value)) {
            $this->tool_data_encrypted = null;

            return;
        }
        $this->tool_data_encrypted = Crypt::encryptString(json_encode($value));
    }

    public function getToolDataAttribute(): ?array
    {
        if (empty($this->tool_data_encrypted)) {
            return null;
        }
        try {
            $decrypted = Crypt::decryptString($this->tool_data_encrypted);

            return json_decode($decrypted, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function scopeForConversation($query, string $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    public function scopeForRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }

    public function toChatArray(): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'content' => $this->content,
            'sources' => $this->sources,
            'tool_used' => $this->tool_used,
            'tool_data' => $this->tool_data,
            'sequence_number' => $this->sequence_number,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
