<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'teacher_id',
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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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
            // Si no hay valor establecido, determinar basado en las fechas
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

            return 'Instructor'; // Categoría por defecto
        }

        return $value;
    }
}
