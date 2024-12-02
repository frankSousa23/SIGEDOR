<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = [
        'name',
        'area',
        'program',
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

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
