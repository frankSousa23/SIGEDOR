<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Programa extends Model
{
    protected $fillable = ['nombre', 'teacher_id'];


    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }



}
