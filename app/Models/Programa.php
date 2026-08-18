<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Programa Académico / Carrera Universitaria.
 *
 * Representa las carreras o programas de estudio impartidos en la institución.
 *
 * @property int $id
 * @property string $nombre
 */
class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas';

    protected $fillable = ['nombre'];

    /**
     * Docentes adscritos al programa.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Asignaciones de sede adscritas a este programa.
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}
