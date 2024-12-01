<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public function getTitle(): string
    {
        return 'Panel de Control';
    }

    public static function getSlug(): string
    {
        return 'dashboard';
    }

    public static function shouldRegister(): bool
    {
        return true; // Todos los usuarios autenticados pueden ver el dashboard base
    }
}
