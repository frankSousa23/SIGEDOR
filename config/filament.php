<?php

return [
    'default_panel' => 'dashboard',
    'panels' => [
        'dashboard' => [
            'id' => 'dashboard',
            'path' => 'dashboard',
            'login' => \Filament\Pages\Auth\Login::class,
            'middleware' => [
                'web',
                \Filament\Http\Middleware\Authenticate::class,
            ],
            'auth_guard' => 'web',
            'database_connection' => env('DB_CONNECTION', 'mysql'),
        ],
    ],
];
