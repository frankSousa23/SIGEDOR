<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Escritorio';
    protected static ?string $title = 'Panel de Control';

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\TeacherStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\TeacherDedicationChart::class,
            \App\Filament\Widgets\TeachersPerCategoryChart::class,
        ];
    }
}
