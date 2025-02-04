<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'area_manager']);
    }

    public function view(User $user, Site $site): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->hasRole('area_manager') && $user->site_id === $site->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Site $site): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Site $site): bool
    {
        return $user->hasRole('admin');
    }
}
