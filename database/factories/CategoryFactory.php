<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher; // Importar el modelo Teacher

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'preTitle' => $this->faker->optional()->sentence(2), // Título de pregrado opcional
            'lastTitle' => $this->faker->optional()->sentence(3), // Título más alto opcional
            'disable_assistant_rule' => false, // Siempre falso para pruebas
            'current_category' => $this->faker->optional()->word(), // Categoría actual opcional
            'instructor' => $this->faker->optional()->date(), // Fecha de instructor opcional
            'asistente' => $this->faker->optional()->date(), // Fecha de asistente opcional
            'agregado' => $this->faker->optional()->date(), // Fecha de agregado opcional
            'asociado' => $this->faker->optional()->date(), // Fecha de asociado opcional
            'titular' => $this->faker->optional()->date(), // Fecha de titular opcional
            'is_active' => true,
            'teachers_count' => $this->faker->numberBetween(0, 5), // Reducir el rango
            'is_available' => true,
            'info' => $this->faker->optional()->sentence(), // Reducir a una oración
            'teacher_id' => null, // Inicialmente, no asignar un teacher_id por defecto en el factory
        ];
    }
}
