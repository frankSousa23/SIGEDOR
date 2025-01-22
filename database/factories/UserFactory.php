<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Site;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'is_active' => $this->faker->boolean(),
            'is_approved' => $this->faker->boolean(),
            'site_id' => Site::factory(),
            // 'cdi' => $this->faker->unique()->randomNumber(8, true),
        ];
    }

    /**
     * Indicate that the user is a teacher.
     */
    public function teacher()
    {
        return $this->state(function (array $attributes) {
            return [];
        });
    }

    /**
     * Indicate that the user is an area manager.
     */
    public function areaManager()
    {
        return $this->state(function (array $attributes) {
            return [];
        });
    }
}
