<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Área Académica / Facultad.
 *
 * Representa las áreas de conocimiento (ej: Ingeniería de Sistemas, Ciencias de la Salud).
 *
 * @property int $id
 * @property string $nombre
 */
class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = ['nombre'];

    /**
     * Usuarios asignados al área.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Docentes asignados al área.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Asignaciones de sede en el área.
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}
