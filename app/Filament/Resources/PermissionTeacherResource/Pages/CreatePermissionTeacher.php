<?php

namespace App\Filament\Resources\PermissionTeacherResource\Pages;

use App\Filament\Resources\PermissionTeacherResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class CreatePermissionTeacher extends CreateRecord
{
    protected static string $resource = PermissionTeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Permiso creado')
            ->body('El permiso ha sido creado exitosamente.');
    }
}
