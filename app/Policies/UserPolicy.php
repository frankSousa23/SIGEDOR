<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
    return $user->hasAnyRole(['admin', 'area_manager']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, User $model)
    {
        if ($user->isAdmin()) return true;

        return $user->isAreaManager() &&
           $user->sede_id === $model->sede_id &&
           $user->areas()->whereIn('id', $model->areas->pluck('id'))->exists();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }

    public function approve(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
