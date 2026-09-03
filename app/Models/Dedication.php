<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Dedicación y Carga Horaria Docente.
 *
 * Representa la modalidad de contratación del profesor (Tiempo Convencional,
 * Medio Tiempo, Tiempo Completo, Exclusiva) y sus horas lectivas/administrativas.
 *
 * Modalidades disponibles: `DEDICATION_TCV`, `DEDICATION_MT`, `DEDICATION_TC`, `DEDICATION_EX`.
 *
 * @property int $id
 * @property string $teacher_cdi CDI del docente asociado
 * @property string $name Nombre de la modalidad de dedicación
 * @property string $type Código corto: TC, MT, TCV, EX
 * @property int $hours Horas semanales asignadas
 * @property bool $is_active Si la dedicación está vigente
 * @property bool $is_available Si está disponible para asignación
 * @property string|null $director Cargo directivo si aplica
 * @property int|null $studentNumber Número de estudiantes
 * @property int|null $studentHours Horas con estudiantes
 * @property string|null $info Información adicional
 */
class Dedication extends Model
{
    use HasFactory;

    protected $table = 'dedications';

    protected $fillable = [
        'teacher_cdi',
        'name',
        'type',
        'hours',
        'director',
        'studentNumber',
        'studentHours',
        'info',
    ];

    protected static function booted(): void
    {
        static::saving(function (Dedication $dedication) {
            if (empty($dedication->type)) {
                $dedication->type = match (trim((string) $dedication->name)) {
                    'Tiempo Completo' => 'TC',
                    'Exclusiva', 'Dedicación Exclusiva' => 'EX',
                    'Medio Tiempo' => 'MT',
                    default => 'TCV',
                };
            }
        });
    }

    protected $casts = [
        'hours' => 'integer',
        'studentNumber' => 'integer',
        'studentHours' => 'integer',
    ];

    // Constantes para tipos de dedicación
    public const DEDICATION_TCV = 'Tiempo Convencional';

    public const DEDICATION_MT = 'Medio Tiempo';

    public const DEDICATION_TC = 'Tiempo Completo';

    public const DEDICATION_EX = 'Exclusiva';

    // Constantes para cargos directivos
    public const DIRECTOR_COORDINATOR = 'Coordinador';

    public const DIRECTOR_DEPARTMENT_HEAD = 'Jefe de Departamento';

    public const DIRECTOR_DEAN = 'Decano';

    public const DIRECTOR_DIRECTOR = 'Director';

    public const DIRECTOR_SUB_DIRECTOR = 'Sub-Director';

    public const DEDICATIONS = [
        'Tiempo Convencional' => 'Tiempo Convencional',
        'Medio Tiempo' => 'Medio Tiempo',
        'Tiempo Completo' => 'Tiempo Completo',
        'Exclusiva' => 'Exclusiva',
    ];

    /**
     * Docente asociado por CDI.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Reportes vinculados a esta dedicación.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Rango de horas válidas según tipo de dedicación.
     */
    public static function getValidHours(string $name): array
    {
        return match ($name) {
            'Tiempo Convencional', 'TCV' => array_combine(range(1, 17), range(1, 17)),
            'Medio Tiempo', 'MT' => [18 => 18],
            'Tiempo Completo', 'TC' => [30 => 30],
            'Exclusiva', 'EX' => [35 => 35, 36 => 36],
            default => [],
        };
    }
}
