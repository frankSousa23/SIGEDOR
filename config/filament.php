<?php

use App\Filament\Pages\Auth\Login;
use Filament\Pages\Auth\Login as FilamentLogin;

return [
    'panels' => [
        'default' => [
            'id' => 'dashboard',
            'path' => 'dashboard',
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],
    ],
];
