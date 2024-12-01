<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Teacher extends Model
{
    //
    protected $fillable = [
        'user_id',
        'cdi', 
        'name', 
        'surName', 
        'genre', 
        'phone', 
        'email', 
        'birthDate', 
        'datePromotion', 
        'asignaturePromotion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->hasOne(Category::class);
    }

    public function dedication(){
        return $this->hasOne(Dedication::class);
    }

    public function permission(){
        return $this->hasOne(Permission::class);
    }

    public function site(){
        return $this->hasOne(Site::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }
}
