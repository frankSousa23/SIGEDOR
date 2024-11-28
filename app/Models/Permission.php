<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $fillable = ['teacher_id', 'permission', 'memoNumber', 'typePermission', 'date', 'rnr'];




    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }

}
