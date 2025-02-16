<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') ||
               ($user->hasRole('area_manager') &&
                $user->sede_id &&
                $user->area_id);
    }

    public function view(User $user, Site $site)
    {
        return $user->hasRole('admin') ||
               ($user->sede_id == $site->sede_id &&
                $user->area_id == $site->area_id);
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Site $site)
    {
        return $user->hasRole('admin') ||
               ($user->hasRole('area_manager') &&
                $user->sede_id == $site->sede_id &&
                $user->area_id == $site->area_id);
    }

    public function delete(User $user, Site $site)
    {
        return $user->hasRole('admin');
    }
}
