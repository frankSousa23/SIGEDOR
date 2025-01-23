<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Teacher; // Importar el modelo Teacher

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Obtener profesores existentes para asignar categorías
        $teachers = Teacher::all();

        Category::factory()
            ->count(5) // Solo 5 categorías para pruebas
            ->create()
            ->each(function ($category) use ($teachers) {
                // Asignar una categoría a un profesor aleatorio (opcional, si las categorías pueden estar relacionadas a profesores)
                if ($teachers->isNotEmpty()) {
                    $category->teacher_id = $teachers->random()->id;
                    $category->save();
                }
            });
    }
}
