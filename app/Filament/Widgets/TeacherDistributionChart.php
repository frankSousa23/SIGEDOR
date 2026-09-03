<?php

namespace App\Filament\Widgets;

use App\Models\Dedication;
use Filament\Widgets\ChartWidget;

class TeacherDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribución de Docentes por Dedicación';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '280px';

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'area_manager']) ?? false;
    }

    public function getHeading(): ?string
    {
        $user = auth()->user();
        if ($user && $user->hasRole('area_manager') && ! $user->hasRole('admin') && $user->sede) {
            return "Distribución de Docentes - Sede {$user->sede->nombre}";
        }

        return static::$heading;
    }

    protected function getData(): array
    {
        $user = auth()->user();
        $isAreaManager = $user && $user->hasRole('area_manager') && ! $user->hasRole('admin');

        $dedications = ['Exclusiva', 'Tiempo Completo', 'Medio Tiempo', 'Tiempo Convencional'];
        $data = [];
        foreach ($dedications as $type) {
            $query = Dedication::query()->where('name', $type);

            if ($isAreaManager && $user->sede_id) {
                $query->whereHas('teacher', function ($q) use ($user) {
                    $q->where('sede_id', $user->sede_id);
                });
            }

            $data[] = $query->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Docentes',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(16, 185, 129)', // Emerald
                        'rgb(59, 130, 246)', // Blue
                        'rgb(245, 158, 11)', // Amber
                        'rgb(107, 114, 128)', // Gray
                    ],
                ],
            ],
            'labels' => $dedications,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
