<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    public static function getNavigationLabel(): string
    {
        return 'Escritorio';
    }

    public function getTitle(): string
    {
        return 'Escritorio';
    }

    protected static ?string $slug = 'dashboard';

    public static function shouldRegister(): bool
    {
        return true;
    }
}
