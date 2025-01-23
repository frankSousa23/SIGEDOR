<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

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
            'description' => $this->faker->optional()->sentence(),
            'is_active' => true, // Siempre activo para pruebas
            'hours' => $this->faker->numberBetween(1, 20), // Reducir el rango
            'director' => $this->faker->optional()->name(),
            'studentNumber' => $this->faker->optional()->numberBetween(1, 10),
            'studentHours' => $this->faker->optional()->numberBetween(1, 20),
            'info' => $this->faker->optional()->sentence(),
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? null,
        ];
    }
}
