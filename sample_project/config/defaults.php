<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Values Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains default values used throughout the application
    | for UI placeholders, fallback values, and initial session data.
    |
    */

    'user' => [
        'first_name' => env('DEFAULT_USER_FIRST_NAME', 'User'),
        'last_name' => env('DEFAULT_USER_LAST_NAME', ''),
        'name' => env('DEFAULT_USER_NAME', 'User'),
        'initial' => env('DEFAULT_USER_INITIAL', 'U'),
        'member_since' => env('DEFAULT_MEMBER_SINCE', 'January 2024'),
    ],

    'avatar' => [
        'fallback_initial' => env('DEFAULT_AVATAR_INITIAL', 'U'),
        'storage_path' => env('DEFAULT_AVATAR_PATH', 'avatars'),
    ],

    'backup' => [
        'storage_path' => env('DEFAULT_BACKUP_PATH', 'backups'),
        'disk' => env('DEFAULT_BACKUP_DISK', 'local'),
    ],

    'session' => [
        'demo_mode' => env('SESSION_DEMO_MODE', false),
        'demo_session_id' => env('SESSION_DEMO_SESSION_ID', 'mock-session-2'),
    ],

    'pagination' => [
        'per_page' => env('DEFAULT_PAGINATION', 20),
    ],

];
