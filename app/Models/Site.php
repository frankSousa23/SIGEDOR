<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'area',
        'program',
        'uc',
        'weekHours',
        'sections',
        'info',
        'is_active',
        'is_available',
        'teachers_count',
        'last_assignment'
    ];

    protected $casts = [
        'weekHours' => 'integer',
        'sections' => 'integer',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'teachers_count' => 'integer',
        'last_assignment' => 'datetime'
    ];

    const SITES = [
        'Sede Central/San Juan de los Morros',
        'Calabozo/Guárico',
        'Valle de la Pascua/Guárico',
        'Zaraza/Guárico'
    ];

    const AREAS = [
        'Ciencias Económicas y Sociales',
        'Ciencias para la Salud',
        'Ingeniería en Sistemas',
        'Ciencias Odontológicas'
    ];

    const PROGRAMAS = [
        'Administración Comercial',
        'Contaduría Pública',
        'Enfermería',
        'Medicina',
        'Informática',
        'Electrónica',
        'Odontología'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
