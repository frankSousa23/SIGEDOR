<?php

namespace App\Filament\Resources\PermissionTeacherResource\Pages;

use App\Filament\Resources\PermissionTeacherResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePermissionTeacher extends CreateRecord
{
    protected static string $resource = PermissionTeacherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['admin', 'area_manager'])) {
            $data['status'] = 'pending';
            if ($user->teacher?->cdi) {
                $data['teacher_cdi'] = $user->teacher->cdi;
            }
        }

        if (empty($data['name'])) {
            $type = $data['type'] ?? 'Permiso';
            $year = now()->format('Y');
            $data['name'] = "{$type} - {$year}";
        }

        return $data;
    }

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
