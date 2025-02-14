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
        'teacher_id',
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
            if (Site::where('teacher_id', $site->teacher_id)->exists()) {
                throw new \Exception('Este docente ya tiene una sede asignada.');
            }
        });
    }


    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function teacher()
    {
    return $this->belongsTo(Teacher::class);
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
