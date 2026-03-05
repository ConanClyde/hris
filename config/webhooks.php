<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Webhook Notification Channels
    |--------------------------------------------------------------------------
    |
    | Configure external notification channels for automated HR alerts.
    | Set SLACK_WEBHOOK_URL and/or TEAMS_WEBHOOK_URL in your .env file.
    |
    */
    'slack' => [
        'enabled' => env('SLACK_WEBHOOK_ENABLED', false),
        'webhook_url' => env('SLACK_WEBHOOK_URL', ''),
    ],

    'teams' => [
        'enabled' => env('TEAMS_WEBHOOK_ENABLED', false),
        'webhook_url' => env('TEAMS_WEBHOOK_URL', ''),
    ],
];
