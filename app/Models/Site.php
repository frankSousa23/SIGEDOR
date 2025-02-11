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


    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }


    public function teacher()
    {
    return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
