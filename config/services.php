<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'calendar_api_key' => env('GOOGLE_CALENDAR_API_KEY'),
    ],

    'google_genai' => [
        'api_key' => env('GOOGLE_API_KEY'),
        'model' => env('GOOGLE_GENAI_MODEL', 'gemini-3.1-flash-lite-preview'),
        'temperature' => (float) env('GOOGLE_GENAI_TEMPERATURE', 0.7),
        // Suggestion: bump this to 2048 or 4096 for HRIS reports
        'max_output_tokens' => (int) env('GOOGLE_GENAI_MAX_OUTPUT_TOKENS', 4096),
    ],

    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'),
        'model' => env('OLLAMA_MODEL', 'llama3.2:3b'),
        'embedding_model' => env('OLLAMA_EMBEDDING_MODEL', 'nomic-embed-text'),
        'temperature' => env('OLLAMA_TEMPERATURE', 0.4),
        'context_size' => env('OLLAMA_CONTEXT_SIZE', 4096),
        'tags_cache_seconds' => env('OLLAMA_TAGS_CACHE_SECONDS', 600),
        'chat_timeout_seconds' => env('OLLAMA_CHAT_TIMEOUT_SECONDS', 60),
        'max_predict' => env('OLLAMA_MAX_PREDICT', 512),
        'max_concurrent' => env('OLLAMA_MAX_CONCURRENT', 2),
        'queue_wait_seconds' => env('OLLAMA_QUEUE_WAIT_SECONDS', 20),
    ],

    'integration_api_key' => env('INTEGRATION_API_KEY', ''),

];
