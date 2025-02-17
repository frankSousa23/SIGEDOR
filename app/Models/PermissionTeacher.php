<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

class PermissionTeacher extends Model
{
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
        'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'string',
        'is_paid' => 'boolean',
        'duration_type' => 'string'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public const TYPES = [
        'Año Sabático',
        'Comisión de Servicio',
        'Renovación o Prórroga',
        'Incapacidad',
        'Por Cuido'
    ];

    public const DURATION_TYPES = [
        'semestral' => 'Semestral (6 meses)',
        'anual' => 'Anual (12 meses)',
        'libre' => 'Libre'
    ];

    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
}

    public function calculateEndDate(): ?string
    {
        if (!$this->start_date || !in_array($this->duration_type, ['semestral', 'anual'])) {
            return null;
        }

        $startDate = Carbon::parse($this->start_date);
        $months = $this->duration_type === 'semestral' ? 6 : 12;
        return $startDate->addMonths($months)->format('Y-m-d');
    }

    public function getDurationLabel(): string
    {
        return self::DURATION_TYPES[$this->duration_type] ?? 'Desconocido';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surName}";
    }
}
