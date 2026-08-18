<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Asignación de Carga / Sede del Docente.
 *
 * Registra la distribución de horas semanales, secciones, unidades de crédito (UC)
 * y ubicación académica del docente.
 *
 * @property int $id
 * @property string $teacher_cdi
 * @property int|null $sede_id
 * @property int|null $area_id
 * @property int|null $programa_id
 * @property int|null $uc
 * @property int|null $weekHours
 * @property int|null $sections
 * @property string|null $info
 * @property bool $is_active
 * @property bool $is_available
 */
class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sites';

    protected $fillable = [
        'teacher_cdi',
        'sede_id',
        'area_id',
        'programa_id',
        'uc',
        'weekHours',
        'sections',
        'info',
        'is_active',
        'is_available',
        'teachers_count',
        'last_assignment',
    ];

    protected $casts = [
        'uc' => 'integer',
        'weekHours' => 'integer',
        'sections' => 'integer',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'teachers_count' => 'integer',
        'last_assignment' => 'datetime',
        'sede_id' => 'integer',
        'area_id' => 'integer',
        'programa_id' => 'integer',
    ];

    /**
     * Docente asociado por CDI.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Sede institucional asignada.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id', 'id');
    }

    /**
     * Área de conocimiento asignada.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    /**
     * Programa o carrera asignada.
     */
    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'programa_id', 'id');
    }

    /**
     * Docentes vinculados directamente a esta sede.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class, 'site_id');
    }
}
