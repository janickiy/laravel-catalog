<?php

return [
    'project_id' => 7,
    'endpoint' => env('UPDATE_ENDPOINT', 'https://license.janickiy.com/'),
    'version' => env('VERSION', '4.0.1'),
    'download_attempts' => 3,
    'retry_delay_ms' => 500,
    'connect_timeout' => 10,
    'download_timeout' => 180,
    'archives' => [
        'update.zip',
        'public.zip',
        'vendor.zip',
    ],
];
