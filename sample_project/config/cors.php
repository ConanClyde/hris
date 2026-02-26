<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control how cross-origin requests are handled. We restrict
    | origins to trusted frontends while allowing all methods and headers so
    | that API integrations remain flexible.
    |
    */

    'paths' => ['api/*', 'broadcasting/auth'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', env('APP_URL', 'http://localhost')),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
