<?php

return [
    'auth' => [
        'guard' => 'web',
        'model' => \App\Models\User::class,
    ],
    'panel' => [
        'path' => 'dashboard',
        'domain' => null,
        'middleware' => [
            'web',
            \Filament\Http\Middleware\Authenticate::class,
        ],
        'auth' => [
            'guard' => 'web',
            'model' => \App\Models\User::class,
        ],
    ],
];
