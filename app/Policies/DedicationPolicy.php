<?php

namespace App\Policies;

use App\Models\Dedication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Política de Seguridad para la gestión de Dedicaciones Docentes.
 * Controla qué usuarios pueden visualizar, asignar o modificar la carga horaria y tipo de dedicación.
 */
class DedicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la lista general de Dedicaciones.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('area_manager');
    }

    /**
     * Determina si el usuario puede ver una dedicación específica.
     */
    public function view(User $user, Dedication $dedication): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            $teacherSedeId = $dedication->teacher?->sede_id ?? $dedication->teacher?->user?->sede_id;

            return $teacherSedeId === $user->sede_id;
        }

        if ($user->hasRole('teacher')) {
            return $dedication->teacher_cdi === $user->teacher?->cdi;
        }

        return false;
    }

    /**
     * Determina si el usuario puede registrar nuevas dedicaciones.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('area_manager');
    }

    /**
     * Determina si el usuario puede actualizar la dedicación.
     */
    public function update(User $user, Dedication $dedication): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            $teacherSedeId = $dedication->teacher?->sede_id ?? $dedication->teacher?->user?->sede_id;

            return $teacherSedeId === $user->sede_id;
        }

        return false;
    }

    /**
     * Determina si el usuario puede eliminar la dedicación.
     */
    public function delete(User $user, Dedication $dedication): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede restaurar la dedicación.
     */
    public function restore(User $user, Dedication $dedication): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente la dedicación.
     */
    public function forceDelete(User $user, Dedication $dedication): bool
    {
        return $user->hasRole('admin');
    }
}
