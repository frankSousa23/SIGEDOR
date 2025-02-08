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
            'middleware' => [
                'web',
                \App\Http\Middleware\Authenticate::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
                \Illuminate\Pipeline\Pipeline::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Illuminate\Auth\Middleware\Authorize::class,
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
     'auth' => [
        'guard' => 'web',
        'pages' => [
            'login' => \App\Http\Controllers\Auth\AuthenticatedSessionController::class,
        ],
        'middleware' => [
            'base' => [
                'auth',
                'filament.auth',
            ],
        ],
    ],
    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
    ],
    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
    ],
];
