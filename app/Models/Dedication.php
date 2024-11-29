<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dedication extends Model
{
    //
    protected $fillable = ['teacher_id', 'dedication', 'tcv', 'mt', 'tc', 'ex', 'hours', 'info'];




    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }

}
