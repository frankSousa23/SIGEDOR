<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Teacher;

class TeacherProfileWidget extends Widget
{
    protected static string $view = 'filament.widgets.teacher-profile-widget';

    public function getTeacher()
    {
        return Teacher::where('user_id', auth()->id())->first();
    }
}
