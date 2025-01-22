<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(2), // Genera frases únicas para el nombre
            'area' => $this->faker->word(), // Palabra aleatoria para el área
            'program' => $this->faker->optional()->sentence(3), // Frase opcional para el programa
            'uc' => $this->faker->optional()->word(), // Palabra opcional para uc
            'weekHours' => $this->faker->numberBetween(10, 40), // Horas semanales aleatorias
            'sections' => $this->faker->numberBetween(1, 10), // Secciones aleatorias
            'info' => $this->faker->optional()->paragraph(), // Párrafo opcional para información
            'is_active' => $this->faker->boolean(), // Booleano aleatorio para activo
            'teachers_count' => $this->faker->numberBetween(0, 50), // Contador de profesores aleatorio
            'is_available' => $this->faker->boolean(), // Booleano aleatorio para disponible
            'last_assignment' => $this->faker->optional()->dateTime(), // Fecha y hora opcional para última asignación
        ];
    }
}
