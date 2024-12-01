<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dedication extends Model
{
    //
    protected $fillable = [
        'dedication',
        'hours',
        'director',
        'studentNumber',
        'teacher_id'
    ];

    protected $casts = [
        'studentNumber' => 'integer',
    ];

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }

}
