<?php

namespace App\Policies;

use App\Models\Dedication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DedicationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Todos pueden ver dedicaciones
    }

    public function view(User $user, Dedication $dedication): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('area_manager')) {
            return $user->site_id === $dedication->user->site_id;
        }
        return $user->id === $dedication->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function update(User $user, Dedication $dedication): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->id === $dedication->user_id;
    }

    public function delete(User $user, Dedication $dedication): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->id === $dedication->user_id;
    }
}
