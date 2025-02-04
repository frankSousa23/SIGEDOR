<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TasksOverview;
use App\Filament\Widgets\AreaStats;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Escritorio';
    protected static ?string $title = 'Escritorio';

    protected function getHeaderWidgets(): array
    {
        return auth()->check() ? $this->resolveWidgets() : [];
    }

    protected function resolveWidgets(): array
    {
        $user = auth()->user();

        return match($user->roles->first()->name) {
            'admin' => [StatsOverview::class, TasksOverview::class],
            'area_manager' => [AreaStats::class],
            default => []
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
