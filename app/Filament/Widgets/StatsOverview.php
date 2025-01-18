<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->getAdminStats();
        }

        if ($user->hasRole('area_manager')) {
            return $this->getAreaManagerStats();
        }

        if ($user->hasRole('teacher')) {
            return $this->getTeacherStats();
        }

        return [];
    }

    private function getAdminStats(): array
    {
        return [
            Stat::make('Total Usuarios', User::count())
                ->description('Todos los usuarios registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Profesores', User::role('teacher')->count())
                ->description('Profesores activos')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Pendientes de Aprobación',
                User::where('is_approved', false)->count())
                ->description('Usuarios sin aprobar')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }

    private function getAreaManagerStats(): array
    {
        $siteId = auth()->user()->site_id;

        return [
            Stat::make('Profesores en mi Sede',
                User::role('teacher')
                    ->where('site_id', $siteId)
                    ->count())
                ->description('Profesores asignados')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            // Más estadísticas específicas para jefes de área
        ];
    }

    private function getTeacherStats(): array
    {
        $userId = auth()->id();

        return [
            // Estadísticas específicas para profesores
            Stat::make('Mi Estado',
                auth()->user()->is_approved ? 'Aprobado' : 'Pendiente')
                ->description('Estado de cuenta')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color(auth()->user()->is_approved ? 'success' : 'warning'),
        ];
    }
}
