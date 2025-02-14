<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin'); // Solo admin puede ver la lista de usuarios
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin puede ver cualquier usuario
        }

        if ($user->hasRole('area_manager')) {
            // Area Manager solo puede ver usuarios de su misma sede y área
            return $model->sede_id === $user->sede_id && $model->area_id === $user->area_id;
        }

        if ($user->hasRole('teacher')) {
            // Teacher solo puede ver su propia información
            return $user->id === $model->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin'); // Solo admin puede crear usuarios
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin puede editar cualquier usuario
        }

        if ($user->hasRole('area_manager')) {
            // Area Manager solo puede editar usuarios de su misma sede y área
            return $model->sede_id === $user->sede_id && $model->area_id === $user->area_id;
        }

        if ($user->hasRole('teacher')) {
            // Teacher solo puede editar su propia información
            return $user->id === $model->id;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('admin'); // Solo admin puede eliminar usuarios
    }

    public function approve(User $user): bool
    {
        return $user->hasRole('admin'); // Solo admin puede aprobar usuarios
    }
}
