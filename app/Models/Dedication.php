<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dedication extends Model
{
    //
    protected $fillable = ['dedication', 'tcv', 'mt', 'tc', 'exclusive', 'info'];




    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }

}
