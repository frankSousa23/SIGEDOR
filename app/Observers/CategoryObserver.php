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


        $category->current_category = $currentCategory;
    }
}
