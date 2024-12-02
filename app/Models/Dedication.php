<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dedication extends Model
{
    protected $fillable = [
        'name',
        'hours',
        'director',
        'studentNumber',
        'studentHours',
        'info',
        'teacher_id'
    ];

    protected $casts = [
        'hours' => 'integer',
        'studentNumber' => 'integer',
        'studentHours' => 'integer',
    ];

    // Constantes para tipos de dedicación
    const DEDICATION_TCV = 'TCV';
    const DEDICATION_MT = 'MT';
    const DEDICATION_TC = 'TC';
    const DEDICATION_EX = 'EX';

    // Constantes para tipos de director
    const DIRECTOR_COORDINATOR = 'Coordinador';
    const DIRECTOR_DEPARTMENT_HEAD = 'Jefe de Departamento';
    const DIRECTOR_DEAN = 'Decano';
    const DIRECTOR_DIRECTOR = 'Director';
    const DIRECTOR_SUB_DIRECTOR = 'Sub-Director';

    // Horas por tipo de dedicación
    const HOURS_RANGES = [
        self::DEDICATION_TCV => [1, 17],
        self::DEDICATION_MT => [18, 18],
        self::DEDICATION_TC => [30, 30],
        self::DEDICATION_EX => [35, 36]
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }

    public static function getValidHours($name)
    {
        return match ($name) {
            self::DEDICATION_TCV => array_combine(range(1, 17), range(1, 17)),
            self::DEDICATION_MT => [18 => 18],
            self::DEDICATION_TC => [30 => 30],
            self::DEDICATION_EX => [35 => 35, 36 => 36],
            default => [],
        };
    }
}
