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

    protected $attributes = [
        'sede_id' => null,
        'area_id' => null,
    ];

    protected static function boot()
    {
    parent::boot();

    static::saving(function ($site) {
        $exists = Site::where('teacher_cdi', $site->teacher_cdi)
            ->where('sede_id', $site->sede_id)
            ->where('area_id', $site->area_id)
            ->where('programa_id', $site->programa_id)
            ->exists();

        if ($exists) {
            throw new \Exception('Configuración duplicada para este docente en la misma sede/área/programa');
        }
    });;
    }


    public function teacher()
{
    return $this->belongsTo(Teacher::class);
}

public function sede()
{
    return $this->belongsTo(Sede::class, 'sede_nombre', 'nombre');
}

public function area()
{
    return $this->belongsTo(Area::class, 'area_nombre', 'nombre');
}

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surName}";
    }
}
