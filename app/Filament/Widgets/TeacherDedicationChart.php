<?php

namespace App\Filament\Widgets;

use App\Models\Dedication;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class TeacherDedicationChart extends ChartWidget
{
    protected static ?string $heading = 'Distribución de Dedicaciones';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $dedications = [
            'TCV' => Dedication::where('dedication', 'TCV')->count(),
            'MT' => Dedication::where('dedication', 'MT')->count(),
            'TC' => Dedication::where('dedication', 'TC')->count(),
            'EX' => Dedication::where('dedication', 'EX')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Docentes por Dedicación',
                    'data' => array_values($dedications),
                    'backgroundColor' => [
                        '#f87171', // Rojo para TCV
                        '#fbbf24', // Amarillo para MT
                        '#60a5fa', // Azul para TC
                        '#34d399', // Verde para EX
                    ],
                ],
            ],
            'labels' => [
                'Tiempo Convencional',
                'Medio Tiempo',
                'Tiempo Completo',
                'Exclusiva',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
