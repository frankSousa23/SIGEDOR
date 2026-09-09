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
 * @property string|null $verification_code
 * @property string $teacher_cdi
 * @property string|null $memoNumber
 * @property string|null $typeReport
 * @property string $status
 * @property string|null $report
 * @property string|null $email
 * @property string|null $info
 * @property int|null $sede_id
 * @property int|null $area_id
 * @property int|null $category_id
 * @property int|null $dedication_id
 * @property int|null $created_by
 */
class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'verification_code',
        'teacher_cdi',
        'memoNumber',
        'typeReport',
        'status',
        'report',
        'email',
        'info',
        'sede_id',
        'area_id',
        'category_id',
        'dedication_id',
        'created_by',
    ];

    protected $casts = [
        'sede_id' => 'integer',
        'area_id' => 'integer',
        'category_id' => 'integer',
        'dedication_id' => 'integer',
        'created_by' => 'integer',
    ];

    /**
     * Boot del modelo para autogeneración de códigos de verificación institucional.
     */
    protected static function booted(): void
    {
        static::creating(function (Report $report): void {
            if (empty($report->verification_code)) {
                $report->verification_code = 'UNERG-REP-'.now()->format('Y').'-'.strtoupper(bin2hex(random_bytes(4)));
            }

            if (empty($report->created_by) && auth()->check()) {
                $report->created_by = auth()->id();
            }

            if (empty($report->status)) {
                $report->status = 'issued';
            }

            if ($report->teacher_cdi) {
                $teacher = Teacher::where('cdi', $report->teacher_cdi)->first();
                if ($teacher) {
                    $report->sede_id ??= $teacher->sede_id;
                    $report->area_id ??= $teacher->area_id;
                    $report->category_id ??= $teacher->category_id ?? Category::first()?->id;
                    $report->dedication_id ??= $teacher->dedication_id ?? Dedication::first()?->id;
                    $report->email ??= $teacher->email;
                }
            }
            $report->category_id ??= Category::first()?->id;
            $report->dedication_id ??= Dedication::first()?->id;

        });
    }

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

    /**
     * Usuario que emitió o registró el reporte.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
