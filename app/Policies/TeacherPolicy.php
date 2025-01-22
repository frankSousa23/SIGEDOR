<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Teacher $teacher): bool
    {
        return $user->id === $teacher->user_id;
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
            return $teacher->site_id === $user->site_id;
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
