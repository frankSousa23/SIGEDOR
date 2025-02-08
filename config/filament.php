<?php

use App\Filament\Pages\Dashboard as CustomDashboard;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;
// use App\Http\Middleware\AuthenticateSession;

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
            'login' => FilamentLogin::class,
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
    'panels' => [
        'main' => [
            'id' => 'main',
            'path' => 'main',
            'login' => FilamentLogin::class,
            'auth_guard' => 'web',
            'middleware' => [
                'web',
                // AuthenticateSession::class,
            ],
            'resources' => [
                // ...
            ],
            'pages' => [
                CustomDashboard::class,
            ],
            'widgets' => [
                // ...
            ],
        ],
    ],
];
