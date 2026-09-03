<?php

namespace App\Policies;

use App\Models\PermissionTeacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Política de Seguridad para la gestión de Permisos y Licencias Docentes.
 * Controla qué usuarios pueden solicitar, visualizar, aprobar o modificar permisos académicos.
 */
class PermissionTeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la lista general de Permisos.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('area_manager') || $user->hasRole('teacher');
    }

    /**
     * Determina si el usuario puede ver un permiso específico.
     */
    public function view(User $user, PermissionTeacher $permission): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            $teacherSedeId = $permission->teacher?->sede_id ?? $permission->teacher?->user?->sede_id;

            return $teacherSedeId === $user->sede_id;
        }

        if ($user->hasRole('teacher')) {
            return $permission->teacher_cdi === $user->teacher?->cdi;
        }

        return false;
    }

    /**
     * Determina si el usuario puede crear solicitudes de permiso.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('area_manager') || $user->hasRole('teacher');
    }

    /**
     * Determina si el usuario puede actualizar el permiso.
     */
    public function update(User $user, PermissionTeacher $permission): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            $teacherSedeId = $permission->teacher?->sede_id ?? $permission->teacher?->user?->sede_id;

            return $teacherSedeId === $user->sede_id;
        }

        // El docente solo puede editar si el permiso aún está en estado pendiente
        if ($user->hasRole('teacher') && $permission->teacher_cdi === $user->teacher?->cdi) {
            return $permission->status === PermissionTeacher::STATUS_PENDING;
        }

        return false;
    }

    /**
     * Determina si el usuario puede eliminar el permiso.
     */
    public function delete(User $user, PermissionTeacher $permission): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede restaurar el permiso.
     */
    public function restore(User $user, PermissionTeacher $permission): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente el permiso.
     */
    public function forceDelete(User $user, PermissionTeacher $permission): bool
    {
        return $user->hasRole('admin');
    }
}
