<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTeacher extends Model
{
    protected $table = 'site_teacher';

    protected $fillable = [
        'site_id',
        'teacher_id'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }


}
