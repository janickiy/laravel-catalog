<?php

return [
    'project_id' => 7,
    'endpoint' => env('UPDATE_ENDPOINT', 'https://license.janickiy.com/'),
    'version' => env('VERSION', '4.0.1'),
    'archives' => [
        'update.zip',
        'public.zip',
        'vendor.zip',
    ],
];
