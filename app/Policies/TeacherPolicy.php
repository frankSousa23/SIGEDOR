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
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return true; // Puede ver todos los teachers de su área
        }

        return false;
    }

    public function view(User $user, Teacher $teacher): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return $teacher->site_id === $user->site_id && $teacher->area_id === $user->area_id;
        }

        if ($user->hasRole('teacher')) {
            return $teacher->user_id === $user->id;
        }

        return false;
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
            return $teacher->site_id === $user->site_id && $teacher->area_id === $user->area_id;
        }

        if ($user->hasRole('teacher')) {
            return $teacher->user_id === $user->id;
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
