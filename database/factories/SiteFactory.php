<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Site;

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
            'name' => $this->faker->unique()->city(),
            'area' => $this->faker->randomElement(['Área 1', 'Área 2']),
            'program' => $this->faker->optional()->randomElement(['Programa 1', 'Programa 2']),
            'uc' => $this->faker->optional()->word(),
            'weekHours' => $this->faker->numberBetween(10, 20),
            'sections' => $this->faker->numberBetween(1, 5),
            'info' => $this->faker->optional()->sentence(),
            'is_active' => true,
            'teachers_count' => 0,
            'is_available' => true,
            'last_assignment' => $this->faker->optional()->dateTime(),
        ];
    }
}
