<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Site;
use App\Models\Report;

class AdminStatisticsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 2; // Formato correcto
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios Activos', User::where('is_active', true)->count())
                ->description('Cuentas activas en el sistema')
                ->chart([7, 2, 5, 4, 3, 10, 5])
                ->color('success'),

            Stat::make('Sedes Registradas', Site::count())
                ->description('Total de ubicaciones')
                ->color('primary'),

            Stat::make('Reportes Pendientes', Report::where('status', 'pending')->count())
                ->description('Requieren revisión')
                ->color('danger')
        ];
    }
}
