<?php

return [
    '1c' => [
        'enabled' => env('INTEGRATION_1C_ENABLED', false),
        'webhook_secret' => env('INTEGRATION_1C_WEBHOOK_SECRET', ''),
        'base_url' => env('INTEGRATION_1C_BASE_URL', ''),
        'api_key' => env('INTEGRATION_1C_API_KEY', ''),
        'sync_entities' => [
            'applicants',
            'students',
            'contracts',
            'teachers',
        ],
        'sync_statuses' => [
            'pending',
            'synced',
            'failed',
            'skipped',
        ],
    ],
];
