<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $auth, User $target): bool
    {
        return $auth->isAdmin() ||
               ($auth->site_option_id == $target->site_option_id &&
                $auth->area_option_id == $target->area_option_id &&
                $auth->hasRole('area_manager')) ||
               ($auth->id == $target->id && $auth->hasRole('teacher'));
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $auth, User $target): bool
    {
        return $auth->isAdmin() ||
              ($auth->hasRole('area_manager') && $auth->site_id == $target->site_id);
    }

    public function delete(User $auth, User $target): bool
    {
        return $auth->isAdmin();
    }

    public function approve(User $user)
    {
        return $user->hasRole('admin');
    }
}
