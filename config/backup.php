<?php

return [
    'disk' => env('BACKUP_DISK', 'local'),
    'path' => env('BACKUP_PATH', 'backups'),
    'retention' => [
        'keep_last' => (int) env('BACKUP_KEEP_LAST', 10),
    ],
];
