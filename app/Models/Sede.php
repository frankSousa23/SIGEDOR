<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Sede Universitaria.
 *
 * Representa los campus, núcleos y extensiones territoriales de la universidad.
 *
 * @property int $id
 * @property string $nombre
 */
class Sede extends Model
{
    use HasFactory;

    protected $table = 'sedes';

    protected $fillable = ['nombre'];

    /**
     * Usuarios pertenecientes a la sede.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Docentes adscritos a la sede.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Asignaciones de carga en esta sede.
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}
