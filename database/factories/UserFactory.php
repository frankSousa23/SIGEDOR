<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Site;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'is_approved' => true,
            'site_option_id' => Site::inRandomOrder()->first()?->id ?? null,
            'role_id' => Role::inRandomOrder()->first()?->id ?? null,
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
            return [
                'role' => 'area_manager',
            ];
        });
    }
}
