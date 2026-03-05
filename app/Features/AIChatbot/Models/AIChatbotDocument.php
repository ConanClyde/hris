<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatbotDocument extends Model
{
    protected $table = 'ai_chatbot_documents';

    protected $fillable = [
        'source',
        'content',
        'tokens',
        'term_count',
        'checksum',
    ];

    protected $casts = [
        'tokens' => 'array',
    ];
}
