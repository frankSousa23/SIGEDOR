<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TasksOverview;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Escritorio';
    protected static ?string $title = 'Escritorio';

    protected function getHeaderWidgets(): array
    {
        return $this->resolveWidgets();
    }

    protected function resolveWidgets(): array
    {
        $user = auth()->user();

        return match(true) {
            $user->hasRole('admin') => $this->adminWidgets(),
            $user->hasRole('area_manager') => $this->areaManagerWidgets(),
            default => $this->defaultWidgets()
        };
    }

    protected function adminWidgets(): array
    {
        return [
            StatsOverview::class,
            TasksOverview::class
        ];
    }

    protected function areaManagerWidgets(): array
    {
        return [
            StatsOverview::class,
            TasksOverview::class
        ];
    }

    protected function defaultWidgets(): array
    {
        return [StatsOverview::class];
    }
}
