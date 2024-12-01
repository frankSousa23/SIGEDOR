<?php

namespace App\Filament\Widgets;

use App\Models\Teacher;
use App\Models\Category;
use Filament\Widgets\ChartWidget;

class TeachersPerCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Docentes por Categoría';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $categories = [
            'Instructor' => Category::where('current_category', 'Instructor')->count(),
            'Asistente' => Category::where('current_category', 'Asistente')->count(),
            'Agregado' => Category::where('current_category', 'Agregado')->count(),
            'Titular' => Category::where('current_category', 'Titular')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Número de Docentes',
                    'data' => array_values($categories),
                    'backgroundColor' => [
                        '#f87171', // Rojo para Instructor
                        '#fbbf24', // Amarillo para Asistente
                        '#60a5fa', // Azul para Agregado
                        '#34d399', // Verde para Titular
                    ],
                ],
            ],
            'labels' => [
                'Instructor',
                'Asistente',
                'Agregado',
                'Titular',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
