<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file controls the visual theming of the application.
    | All brand colors, typography, and UI constants can be customized
    | via environment variables for easy white-labeling.
    |
    */

    'colors' => [
        'primary' => env('THEME_PRIMARY', '#013CFC'),
        'primary_dark' => env('THEME_PRIMARY_DARK', '#0031BC'),
        'primary_light' => env('THEME_PRIMARY_LIGHT', '#60C8FC'),

        'success' => env('THEME_SUCCESS', '#10b981'),
        'warning' => env('THEME_WARNING', '#f59e0b'),
        'danger' => env('THEME_DANGER', '#ef4444'),
        'info' => env('THEME_INFO', '#3b82f6'),

        'gray' => [
            '50' => '#f9fafb',
            '100' => '#f3f4f6',
            '200' => '#e5e7eb',
            '300' => '#d1d5db',
            '400' => '#9ca3af',
            '500' => '#6b7280',
            '600' => '#4b5563',
            '700' => '#374151',
            '800' => '#1f2937',
            '900' => '#111827',
            '950' => '#030712',
        ],
    ],

    'dark_mode' => [
        'enabled' => env('THEME_DARK_MODE', true),
        'default' => env('THEME_DARK_MODE_DEFAULT', false),
    ],

    'app' => [
        'name' => env('APP_NAME', 'HRIS'),
        'logo' => env('THEME_LOGO', null),
        'favicon' => env('THEME_FAVICON', null),
    ],

];
