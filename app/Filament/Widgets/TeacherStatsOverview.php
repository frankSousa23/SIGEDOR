<?php

namespace App\Filament\Widgets;

use App\Models\Teacher;
use App\Models\Dedication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TeacherStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalTeachers = Teacher::count();
        $withDedication = Dedication::count();
        $withDirectorRole = Dedication::whereNotNull('director')->count();
        $withAdvisory = Dedication::whereNotNull('studentNumber')->count();

        return [
            Stat::make('Total Docentes', $totalTeachers)
                ->description('Docentes registrados en el sistema')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([7, 4, 6, $totalTeachers])
                ->color('success'),
            
            Stat::make('Con Dedicación Asignada', $withDedication)
                ->description('Docentes con dedicación definida')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 3, 4, $withDedication])
                ->color('info'),
            
            Stat::make('Cargos Directivos', $withDirectorRole)
                ->description('Docentes con cargos de dirección')
                ->descriptionIcon('heroicon-m-user-circle')
                ->chart([1, 2, 1, $withDirectorRole])
                ->color('warning'),
                
            Stat::make('Con Asesorías', $withAdvisory)
                ->description('Docentes realizando asesorías')
                ->descriptionIcon('heroicon-m-users')
                ->chart([2, 4, 3, $withAdvisory])
                ->color('success'),
        ];
    }
}
