<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    //
    protected $fillable = ['teacher_id', 'site', 'area', 'program', 'uc', 'weekHours', 'sections', 'info'];




    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
}
