<?php

return [
    'path' => 'admin',
    'home_url' => '/',
    'domain' => env('FILAMENT_DOMAIN'),
    'middleware' => [
        'base' => ['web'],
        'auth' => ['auth'],
    ],
    'auth' => [
        'guard' => 'web',
        'pages' => [
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],
        'middleware' => [
            'base' => [
                'auth',
                'filament.auth',
            ],
        ],
    ],
    'layout' => [
        'assets' => [
            'vite' => [
                'input' => [
                    'resources/css/app.css',
                    'resources/js/app.js',
                ],
                'refresh' => true,
            ],
        ],
    ],
    'panels' => [
        'dashboard' => [
            'id' => 'dashboard',
            'path' => 'dashboard',
            'login' => \Filament\Http\Livewire\Auth\Login::class,
            'auth_guard' => 'web',
            'middleware' => [
                'web',
                AuthenticateSession::class,
            ],
            'resources' => [
                // ...
            ],
            'pages' => [
                \Filament\Pages\Dashboard::class,
            ],
            'widgets' => [
                // ...
            ],
        ],
    ],
    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
    ],
    'livewire' => [],
    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
    ],
    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
    ],
];
