<?php

namespace App\Filament\Widgets;

use App\Models\Sede;
use Filament\Widgets\ChartWidget;

class SedeStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Personal y Docentes por Sede Universitaria';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '280px';

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    protected function getData(): array
    {
        $sedes = Sede::withCount('teachers')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Docentes',
                    'data' => $sedes->pluck('teachers_count')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $sedes->pluck('nombre')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
