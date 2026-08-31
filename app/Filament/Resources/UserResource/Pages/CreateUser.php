<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Sede;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Usuario creado exitosamente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sede_id'] = (int) $data['sede_id']; // Forzar tipo correcto

        // Validación adicional
        if (! Sede::where('id', $data['sede_id'])->exists()) {
            throw new \Exception('La sede seleccionada no existe');
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Asignación de rol por nombre usando ID seleccionado
        if (isset($this->data['roles'])) {
            $role = Role::findById($this->data['roles']);
            $this->record->syncRoles([$role->name]);
        }

        // Sincronización de área única
        if (isset($this->data['areas'])) {
            $this->record->areas()->sync([$this->data['areas']]);
        }
    }
}
