<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Site;
use App\Models\AreaOption;
use App\Models\SiteOption;

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
            'name' => $this->faker->unique()->city,
            'site_option_id' => SiteOption::factory(),
            'area_id' => AreaOption::factory(),
            'is_active' => true,
            'program' => $this->faker->optional()->randomElement(['Programa 1', 'Programa 2']),
            'uc' => $this->faker->optional()->word(),
            'weekHours' => $this->faker->numberBetween(10, 20),
            'sections' => $this->faker->numberBetween(1, 5),
            'info' => $this->faker->optional()->sentence(),
            'teachers_count' => 0,
            'is_available' => true,
            'last_assignment' => $this->faker->optional()->dateTime(),
        ];
    }
}
