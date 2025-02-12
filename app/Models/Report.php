<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'category_id',
        'dedication_id',
        'sede_id',
        'area_id',
        'report',
        'memoNumber',
        'typeReport',
        'email',
        'info',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dedication()
    {
        return $this->belongsTo(Dedication::class);
    }

    public function permissionTeacher()
    {
        return $this->belongsTo(PermissionTeacher::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
