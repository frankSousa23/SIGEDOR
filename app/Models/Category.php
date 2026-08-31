<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Categoría Académica Docente.
 *
 * Registra el escalafón universitario del profesorado (Instructor, Asistente,
 * Agregado, Asociado, Titular), títulos de pregrado/postgrado y fechas de ascenso.
 *
 * @property int $id
 * @property string $teacher_cdi
 * @property string|null $category
 * @property string|null $preTitle
 * @property string|null $lastTitle
 * @property string|null $current_category
 * @property \Carbon\Carbon|null $instructor
 * @property \Carbon\Carbon|null $asistente
 * @property \Carbon\Carbon|null $agregado
 * @property \Carbon\Carbon|null $asociado
 * @property \Carbon\Carbon|null $titular
 * @property string|null $info
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'teacher_cdi',
        'category',
        'preTitle',
        'lastTitle',
        'current_category',
        'instructor',
        'asistente',
        'agregado',
        'asociado',
        'titular',
        'info',
    ];

    protected $casts = [
        'instructor' => 'date',
        'asistente' => 'date',
        'agregado' => 'date',
        'asociado' => 'date',
        'titular' => 'date',
    ];

    public const CATEGORIES = [
        'Instructor' => 'Instructor',
        'Asistente' => 'Asistente',
        'Agregado' => 'Agregado',
        'Asociado' => 'Asociado',
        'Titular' => 'Titular',
    ];

    /**
     * Docente titular de la categoría.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Reportes asociados a la categoría.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Obtiene la categoría docente calculada más alta según las fechas registradas.
     */
    public function getCurrentCategoryAttribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        $dates = [
            'Titular' => $this->titular,
            'Asociado' => $this->asociado,
            'Agregado' => $this->agregado,
            'Asistente' => $this->asistente,
            'Instructor' => $this->instructor,
        ];

        foreach ($dates as $category => $date) {
            if ($date) {
                return $category;
            }
        }

        return 'Instructor';
    }

    /**
     * Registro de auditoría ante cambios en escalafón.
     */
    protected static function booted()
    {
        static::updating(function ($category) {
            if (function_exists('activity') && $category->isDirty(['instructor', 'asistente', 'agregado', 'asociado', 'titular'])) {
                activity()
                    ->performedOn($category)
                    ->withProperties($category->getChanges())
                    ->log('Actualización de escalafón docente');
            }
        });
    }
}
