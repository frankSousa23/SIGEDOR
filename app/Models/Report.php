<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de Reportes y Memorandos Docentes.
 *
 * Registra los informes oficiales, constancias y documentos generados
 * para trámites académicos y administrativos.
 *
 * @property int $id
 * @property string $teacher_cdi
 * @property string|null $memoNumber
 * @property string|null $typeReport
 * @property string|null $report
 * @property string|null $email
 * @property string|null $info
 * @property int|null $sede_id
 * @property int|null $area_id
 * @property int|null $category_id
 * @property int|null $dedication_id
 */
class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'teacher_cdi',
        'memoNumber',
        'typeReport',
        'report',
        'email',
        'info',
        'sede_id',
        'area_id',
        'category_id',
        'dedication_id',
    ];

    protected $casts = [
        'sede_id' => 'integer',
        'area_id' => 'integer',
        'category_id' => 'integer',
        'dedication_id' => 'integer',
    ];

    /**
     * Docente asociado al reporte.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Sede asociada.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    /**
     * Área académica asociada.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * Categoría asociada.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Dedicación asociada.
     */
    public function dedication(): BelongsTo
    {
        return $this->belongsTo(Dedication::class, 'dedication_id');
    }
}
