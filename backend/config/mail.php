<?php

return [
    'mailers' => [
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@ppseducrm.local'),
        'name' => env('MAIL_FROM_NAME', 'PPSEDUCRM'),
    ],
];
