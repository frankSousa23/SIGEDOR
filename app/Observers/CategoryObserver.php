<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    private array $categoryHierarchy = [
        'Titular' => 'titular',
        'Asociado' => 'asociado',
        'Agregado' => 'agregado',
        'Asistente' => 'asistente',
        'Instructor' => 'instructor',
    ];

    public function saving(Category $category): void
    {
        $this->determineCurrentCategory($category);
    }

    private function determineCurrentCategory(Category $category): void
    {
        $currentCategory = 'Instructor';

        foreach ($this->categoryHierarchy as $cat => $field) {
            if (! is_null($category->$field)) {
                $currentCategory = $cat;
                break;
            }
        }

        $category->current_category = $currentCategory;
    }
}
