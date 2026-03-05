<?php

namespace App\Features\AIChatbot\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatbotMetric extends Model
{
    protected $table = 'ai_chatbot_metrics';

    protected $fillable = [
        'user_id',
        'role',
        'query_hash',
        'context_ms',
        'llm_ms',
        'total_ms',
        'policy_sources_count',
        'max_confidence',
        'cache_hit',
        'error_type',
    ];

    protected $casts = [
        'cache_hit' => 'boolean',
        'max_confidence' => 'float',
    ];
}
