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
            'disable_assistant_rule' => $this->faker->boolean(), // Regla de asistente deshabilitada aleatoria
            'current_category' => $this->faker->optional()->word(), // Categoría actual opcional
            'instructor' => $this->faker->optional()->date(), // Fecha de instructor opcional
            'asistente' => $this->faker->optional()->date(), // Fecha de asistente opcional
            'agregado' => $this->faker->optional()->date(), // Fecha de agregado opcional
            'asociado' => $this->faker->optional()->date(), // Fecha de asociado opcional
            'titular' => $this->faker->optional()->date(), // Fecha de titular opcional
            'is_active' => $this->faker->boolean(), // Estado activo aleatorio
            'teachers_count' => $this->faker->numberBetween(0, 10), // Contador de profesores aleatorio
            'is_available' => $this->faker->boolean(), // Disponibilidad aleatoria
            'info' => $this->faker->optional()->paragraph(), // Información adicional opcional
            // 'teacher_id' => Teacher::factory(), // Opcional: Si quieres crear un Teacher relacionado con cada Category
            'teacher_id' => null, // Inicialmente, no asignar un teacher_id por defecto en el factory
        ];
    }
}
