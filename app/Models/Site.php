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
        'info'
    ];

    protected $casts = [
        'weekHours' => 'integer',
        'sections' => 'integer',
    ];

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
