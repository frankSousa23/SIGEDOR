<?php

namespace App\Filament\Widgets;

use App\Models\PermissionTeacher;
use App\Models\Report;
use App\Models\Teacher;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // Todos los roles autenticados ven su propia versión del widget.
        // La lógica diferenciada por rol está en getStats().
        return auth()->check();
    }

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
        $user = auth()->user();
        $sedeId = $user->sede_id;

        $teachersCount = Teacher::where('sede_id', $sedeId)->count();

        $pendingPermissionsCount = PermissionTeacher::where('status', PermissionTeacher::STATUS_PENDING)
            ->whereHas('teacher', fn ($q) => $q->where('sede_id', $sedeId))
            ->count();

        $reportsCount = Report::where('sede_id', $sedeId)->count();

        return [
            Stat::make('Docentes en mi Sede', $teachersCount)
                ->description('Plantilla adscrita')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Permisos Pendientes', $pendingPermissionsCount)
                ->description('Solicitudes por revisar')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($pendingPermissionsCount > 0 ? 'warning' : 'success'),

            Stat::make('Reportes Emitidos', $reportsCount)
                ->description('Dictámenes y memorandos')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('secondary'),
        ];
    }

    private function getTeacherStats(): array
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        return [
            Stat::make('Mi Estado', $user->is_approved ? 'Aprobado' : 'Pendiente')
                ->description('Estado de cuenta institucional')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color($user->is_approved ? 'success' : 'warning'),

            Stat::make('Escalafón', $teacher?->category?->current_category ?? 'Instructor')
                ->description('Categoría docente actual')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('primary'),

            Stat::make('Dedicación', $teacher?->dedication?->name ?? 'Sin Asignar')
                ->description('Carga horaria asignada')
                ->descriptionIcon('heroicon-m-clock')
                ->color('secondary'),
        ];
    }
}
