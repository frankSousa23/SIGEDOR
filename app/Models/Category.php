<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['teacher_id', 'category', 'instructor', 'asistente', 'agregado', 'asociado', 'titular', 'info'];





    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }

}
