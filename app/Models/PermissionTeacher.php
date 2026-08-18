<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo de Permisos y Licencias de Docentes.
 *
 * Registra solicitudes, prórrogas, años sabáticos, comisiones de servicio e
 * incapacidades del personal docente con su estado y período de vigencia.
 *
 * @property int $id
 * @property string $teacher_cdi
 * @property string|null $memo_number
 * @property string $type
 * @property bool $is_paid
 * @property string|null $name
 * @property string $status
 * @property string|null $duration_type
 * @property \Carbon\Carbon|null $start_date
 * @property \Carbon\Carbon|null $end_date
 * @property string|null $description
 */
class PermissionTeacher extends Model
{
    use HasFactory;

    protected $table = 'permissionsteachers';

    protected $fillable = [
        'teacher_cdi',
        'memo_number',
        'type',
        'is_paid',
        'name',
        'status',
        'duration_type',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'string',
        'is_paid' => 'boolean',
        'duration_type' => 'string',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const TYPES = [
        'Año Sabático',
        'Comisión de Servicio',
        'Renovación o Prórroga',
        'Incapacidad',
        'Por Cuido',
    ];

    public const DURATION_TYPES = [
        'semestral' => 'Semestral (6 meses)',
        'anual' => 'Anual (12 meses)',
        'libre' => 'Libre',
    ];

    /**
     * Docente solicitante por CDI.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Calcula automáticamente la fecha de fin según la duración seleccionada.
     */
    public function calculateEndDate(): ?string
    {
        if (!$this->start_date || !in_array($this->duration_type, ['semestral', 'anual'])) {
            return null;
        }

        $startDate = Carbon::parse($this->start_date);
        $months = $this->duration_type === 'semestral' ? 6 : 12;

        return $startDate->addMonths($months)->format('Y-m-d');
    }

    /**
     * Etiqueta amigable del tipo de duración.
     */
    public function getDurationLabel(): string
    {
        return self::DURATION_TYPES[$this->duration_type] ?? 'Desconocido';
    }
}
