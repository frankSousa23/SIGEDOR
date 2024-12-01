<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;

class TeacherPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the list, but it will be filtered
    }

    public function view(User $user, Teacher $teacher): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAreaManager()) {
            // Double validation: check site and area from site table
            return $teacher->site_id === $user->getSiteId() &&
                   $teacher->site->area === $user->getArea();
        }

        // Teachers can only view their own information
        return $user->isTeacher() && $teacher->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only admin can create
    }

    public function update(User $user, Teacher $teacher): bool
    {
        return $user->isAdmin(); // Only admin can update
    }

    public function delete(User $user, Teacher $teacher): bool
    {
        return $user->isAdmin(); // Only admin can delete
    }

    public function export(User $user): bool
    {
        return true; // All users can export their visible data
    }
}
