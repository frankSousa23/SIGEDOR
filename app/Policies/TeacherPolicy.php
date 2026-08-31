<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Política de Seguridad para la gestión de Docentes.
 * Define qué usuarios tienen acceso a visualizar, crear, editar o eliminar registros de Docentes.
 */
class TeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la lista general de Docentes.
     * Solo Administradores y Jefes de Área con sede asignada.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') ||
               ($user->hasRole('area_manager') && $user->sede_id && $user->area_id);
    }

    public function view(User $user, Teacher $teacher)
    {
        return $user->hasRole('admin') ||
               ($user->hasRole('area_manager') &&
                $teacher->sede_id == $user->sede_id &&
                $teacher->area_id == $user->area_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Teacher $teacher): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return $teacher->sede_id === $user->sede_id;
        }

        if ($user->hasRole('teacher')) {
            return $teacher->cdi === $user->cdi;
        }

        return false;
    }

    public function delete(User $user, Teacher $teacher): bool
    {
        return $user->hasRole('admin');
    }

    public function export(User $user): bool
    {
        return true; // All users can export their visible data
    }
}
