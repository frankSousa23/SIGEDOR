<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class AreaManagerDashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\TeacherStatsOverview::class,
            \App\Filament\Widgets\TeacherDedicationChart::class,
        ];
    }

    public function getTitle(): string 
    {
        return 'Panel de Control - Área';
    }

    public static function shouldRegister(): bool
    {
        return auth()->user()->isAreaManager();
    }
}
