<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'area_manager', 'teacher']); // Todos los roles pueden ver reports
    }

    public function view(User $user, Report $report): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin puede ver cualquier report
        }

        if ($user->hasRole('area_manager')) {
            // Area Manager solo puede ver reports de su misma sede y área
            return $report->sede_id === $user->sede_id && $report->area_id === $user->area_id;
        }

        if ($user->hasRole('teacher')) {
            // Teacher solo puede ver sus propios reports
            return $report->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'area_manager', 'teacher']); // Todos los roles pueden crear reports
    }

    public function update(User $user, Report $report): bool
    {
        if ($user->hasRole('admin')) {
            return true; // Admin puede editar cualquier report
        }

        if ($user->hasRole('area_manager')) {
            // Area Manager solo puede editar reports de su misma sede y área
            return $report->sede_id === $user->sede_id && $report->area_id === $user->area_id;
        }

        if ($user->hasRole('teacher')) {
            // Teacher solo puede editar sus propios reports
            return $report->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole('admin'); // Solo admin puede eliminar reports
    }
}
