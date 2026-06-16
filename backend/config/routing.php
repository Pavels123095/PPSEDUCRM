<?php

return [
    'paths' => [
        'api' => ['prefix' => 'api', 'middleware' => 'api'],
    ],

    'middleware' => [
        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ],
];
