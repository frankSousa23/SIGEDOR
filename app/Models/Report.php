<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'teacher_cdi',
        'memoNumber',
        'typeReport',
        'report',
        'email',
        'info',
        'sede_id',
        'area_id',
        'category_id',
        'dedication_id'
    ];

    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
}
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function dedication()
    {
        return $this->belongsTo(Dedication::class, 'dedication_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surName}";
    }

}
