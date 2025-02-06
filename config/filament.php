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
    //'default' => 'dashboard', Comentado para usar la configuración predeterminada
    // 'panels' => [
    //     'dashboard' => [
    //         'id' => 'dashboard',
    //         'path' => 'admin',
    //         //'login' => \App\Filament\Pages\Auth\Login::class,
    //         'registration' => null,
    //         'middleware' => [
    //             'web',
    //             'auth' => \Filament\Http\Middleware\Authenticate::class,
    //             'session' => \Illuminate\Session\Middleware\StartSession::class,
    //         ],
    //         'auth_guard' => 'web',
    //         'database_connection' => env('DB_CONNECTION', 'mysql'),
    //     ],
    // ],
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
