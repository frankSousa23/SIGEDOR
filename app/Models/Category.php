<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'teacher_cdi',
        'category',
        'preTitle',
        'lastTitle',
        'disable_assistant_rule',
        'current_category',
        'instructor',
        'asistente',
        'agregado',
        'asociado',
        'titular',
        'info'
    ];

    protected $casts = [
        'disable_assistant_rule' => 'boolean',
        'instructor' => 'date',
        'asistente' => 'date',
        'agregado' => 'date',
        'asociado' => 'date',
        'titular' => 'date',
    ];

    const CATEGORIES = [
        'Instructor' => 'Instructor',
        'Asistente' => 'Asistente',
        'Agregado' => 'Agregado',
        'Asociado' => 'Asociado',
        'Titular' => 'Titular',
    ];

    protected $dates = [
        'instructor',
        'asistente', 
        'agregado',
        'asociado',
        'titular',
        'created_at',
        'updated_at'
    ];

    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_cdi', 'cdi');
}

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function shouldApplyAssistantRule()
    {
        return !$this->disable_assistant_rule;
    }

    public function updateCurrentCategory($categoryName)
    {
        $this->current_category = $categoryName;
        $this->save();
    }

    public function getCurrentCategoryAttribute($value)
    {
        if (!$value) {

            $dates = [
                'titular' => $this->titular,
                'asociado' => $this->asociado,
                'agregado' => $this->agregado,
                'asistente' => $this->asistente,
                'instructor' => $this->instructor,
            ];

            foreach ($dates as $category => $date) {
                if ($date) {
                    return ucfirst($category);
                }
            }

            return 'Instructor';
        }

        return $value;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surName}";
    }

    protected static function booted()
{
    static::updating(function ($category) {
        if ($category->isDirty(['instructor', 'asistente', 'agregado', 'asociado', 'titular'])) {
            activity()
                ->performedOn($category)
                ->withProperties($category->getChanges())
                ->log('Actualización de categoría');
        }
    });
}
}
