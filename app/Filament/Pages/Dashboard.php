<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TasksOverview;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function getNavigationLabel(): string
    {
        return 'Escritorio';
    }

    public function getTitle(): string
    {
        return 'Escritorio';
    }

    protected static ?string $slug = 'dashboard';

    public static function shouldRegister(): bool
    {
        return true;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $user = auth()->user();
        $totalTeachers = Teacher::count(); // Total de docentes
        $approvedPermissions = PermissionTeacher::where('status', 'approved')->count(); // Permisos aprobados
        $activeUsers = User::where('is_active', true)->count(); // Usuarios activos

        return view('filament.pages.dashboard', [
            'isAdmin' => $user->isAdmin(),
            'isAreaManager' => $user->isAreaManager(),
            'isTeacher' => $user->isTeacher(),
            'totalTeachers' => $totalTeachers,
            'approvedPermissions' => $approvedPermissions,
            'activeUsers' => $activeUsers,
        ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TasksOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return [
                StatsOverview::class,
                TasksOverview::class,
            ];
        }

        if ($user->hasRole('area_manager')) {
            return [
                StatsOverview::class,
                TasksOverview::class,
            ];
        }

        return [
            StatsOverview::class,
        ];
    }
}
