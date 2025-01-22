<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dedication>
 */
class DedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(), // Nombre único para la dedicación
            'description' => $this->faker->optional()->paragraph(), // Descripción opcional
            'is_active' => $this->faker->boolean(), // Estado activo aleatorio
            'hours' => $this->faker->numberBetween(1, 40), // Añadimos la columna 'hours' con un valor aleatorio entre 1 y 40
        ];
    }
}
