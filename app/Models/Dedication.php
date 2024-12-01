<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dedication extends Model
{
    protected $fillable = [
        'dedication',
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

    // Constantes para cargos directivos
    const DIRECTOR_COORDINATOR = 'Coordinador';
    const DIRECTOR_DEPARTMENT_HEAD = 'Jefe de Departamento';
    const DIRECTOR_DEAN = 'Decano';

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

    public static function getValidHours(?string $dedicationType): array
    {
        if (!$dedicationType || !isset(self::HOURS_RANGES[$dedicationType])) {
            return [];
        }

        $range = self::HOURS_RANGES[$dedicationType];
        if ($range[0] === $range[1]) {
            return [$range[0] => $range[0]];
        }

        return array_combine(
            range($range[0], $range[1]),
            range($range[0], $range[1])
        );
    }
}
