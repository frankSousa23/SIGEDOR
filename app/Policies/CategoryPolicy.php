<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Política de Seguridad para la gestión de Categorías Docentes.
 * Controla qué usuarios pueden visualizar, asignar o modificar el escalafón de los profesores.
 */
class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la lista general de Categorías.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('area_manager');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return $category->teacher->user->sede_id === $user->sede_id;
        }

        if ($user->hasRole('teacher')) {
            return $category->teacher_cdi === $user->teacher->cdi;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return $category->teacher->user->sede_id === $user->sede_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }
}
