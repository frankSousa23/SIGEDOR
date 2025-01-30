<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Lógica para determinar si el usuario puede ver *alguna* categoría
        if ($user->hasRole('admin')) {
            return true; // Admin puede ver todas
        }
        // Otra lógica basada en roles, site_option, area_option, etc.
        return false; // Por defecto, no puede ver ninguna
    }

    public function view(User $user, Category $category): bool
    {
        // Lógica para determinar si el usuario puede ver *esta* categoría específica
        if ($user->hasRole('admin')) {
            return true; // Admin puede ver todas
        }
        // Lógica basada en si la categoría pertenece a su site_option y area_option
        return $category->site_option_id === $user->site_option_id && $category->area_option_id === $user->area_option_id;
    }

    // ... other methods (create, update, delete, etc.) ...
}
