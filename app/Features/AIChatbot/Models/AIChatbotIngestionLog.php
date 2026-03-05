<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatbotIngestionLog extends Model
{
    protected $table = 'ai_chatbot_ingestion_logs';

    protected $fillable = [
        'user_id',
        'embed',
        'documents_indexed',
        'chunks_created',
        'duration_ms',
        'status',
        'error_message',
    ];

    protected $casts = [
        'embed' => 'boolean',
    ];
}
