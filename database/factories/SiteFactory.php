<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'area' => $this->faker->randomElement(['Matemáticas', 'Física', 'Química', 'Biología', 'Historia']),
            'is_active' => true,
        ];
    }
}
