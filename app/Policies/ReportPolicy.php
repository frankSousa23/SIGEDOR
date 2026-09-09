<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'area_manager', 'teacher']);
    }

    public function view(User $user, Report $report): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return $report->sede_id === $user->sede_id
                || ($report->teacher && $report->teacher->sede_id === $user->sede_id);
        }

        if ($user->hasRole('teacher')) {
            return $report->teacher_cdi === $user->teacher?->cdi
                || ($report->teacher && $report->teacher->user_id === $user->id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'area_manager', 'teacher']);
    }

    public function update(User $user, Report $report): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('area_manager')) {
            return ($report->sede_id === $user->sede_id || ($report->teacher && $report->teacher->sede_id === $user->sede_id))
                && $report->status !== 'annulled';
        }

        return false;
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole('admin');
    }
}
