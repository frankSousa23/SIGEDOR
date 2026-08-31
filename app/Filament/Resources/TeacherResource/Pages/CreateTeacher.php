<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Docente registrado exitosamente';
    }
}
