<?php

return [
    'default' => 'dashboard',
    'panels' => [
        'dashboard' => [
            'id' => 'dashboard',
            'path' => 'dashboard',
            'login' => \App\Filament\Pages\Auth\Login::class,
            'registration' => null,
            'middleware' => ['web'],
            'auth_guard' => 'web',
            'database_connection' => env('DB_CONNECTION', 'mysql'),
        ],
    ],
    'widgets' => [],
    'livewire' => [],
];
