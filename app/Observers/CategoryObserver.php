<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    private $categoryHierarchy = [
        'TITULAR' => 'titular',
        'ASOCIADO' => 'asociado',
        'AGREGADO' => 'agregado',
        'ASISTENTE' => 'asistente',
        'INSTRUCTOR' => 'instructor'
    ];

    public function saving(Category $category)
    {
        $this->determineCurrentCategory($category);
    }

    private function determineCurrentCategory(Category $category)
    {
        $currentCategory = 'INSTRUCTOR';

        foreach ($this->categoryHierarchy as $cat => $field) {
            if (!is_null($category->$field)) {
                $currentCategory = $cat;
                break;
            }
        }

        // Regla especial para saltar ASISTENTE
        if ($category->disable_assistant_rule) {
            if (!is_null($category->agregado)) {
                $currentCategory = 'AGREGADO';
            } elseif (!is_null($category->asociado)) {
                $currentCategory = 'ASOCIADO';
            } elseif (!is_null($category->titular)) {
                $currentCategory = 'TITULAR';
            }
        }

        $category->current_category = $currentCategory;
    }
}
