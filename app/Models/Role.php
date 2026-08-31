<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo de Rol del Sistema (Spatie Permission).
 *
 * Extiende el modelo de roles de Spatie con soporte para la asociación
 * con usuarios del sistema y sus relaciones de sede/área.
 *
 * Roles disponibles: admin, area_manager, teacher.
 */
class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['headquarters_id', 'area_id'])
            ->withTimestamps();
    }
}
