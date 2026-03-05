<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatbotChunk extends Model
{
    protected $table = 'ai_chatbot_chunks';

    protected $fillable = [
        'document_id',
        'source',
        'chunk_index',
        'content',
        'embedding',
        'token_count',
        'visibility',
        'checksum',
    ];

    protected $casts = [
        'embedding' => 'array',
        'visibility' => 'array',
    ];
}
