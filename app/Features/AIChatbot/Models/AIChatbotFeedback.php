<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatbotFeedback extends Model
{
    protected $table = 'ai_chatbot_feedback';

    protected $fillable = [
        'user_id',
        'role',
        'query_hash',
        'message_id',
        'prompt',
        'rating',
        'response_excerpt',
        'sources',
    ];

    protected $casts = [
        'sources' => 'array',
    ];
}
