<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $fillable = ['nombre'];

    // Relación opcional según necesidades
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
