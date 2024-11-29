<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = ['teacher_id', 'category_id', 'dedication_id', 'permission_id', 'site_id', 'report', 'memoNumber', 'typeReport', 'email', 'info'];




    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function dedication(){
        return $this->belongsTo(Dedication::class);
    }

    public function site(){
        return $this->belongsTo(Site::class);
    }
}
