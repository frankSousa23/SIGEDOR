<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class TeacherDashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\TeacherProfileWidget::class,
        ];
    }

    public function getTitle(): string 
    {
        return 'Mi Perfil';
    }

    public static function shouldRegister(): bool
    {
        return auth()->user()->isTeacher();
    }
}
