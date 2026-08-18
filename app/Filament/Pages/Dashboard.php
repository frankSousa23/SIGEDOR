<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TasksOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

/**
 * Página principal de Escritorio / Dashboard en Filament.
 */
class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function getNavigationLabel(): string
    {
        return 'Escritorio';
    }

    public function getTitle(): string
    {
        return 'Escritorio Principal';
    }

    protected static ?string $slug = 'dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            TasksOverview::class,
        ];
    }
}
