<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Site;
use App\Models\User;

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
            'sede_id' => \App\Models\Sede::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'is_approved' => true,
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

    public function configure()
    {
    return $this->afterCreating(function (User $user) {
        $user->areas()->attach(
            \App\Models\Area::inRandomOrder()->take(2)->pluck('id')
        );
    });
    }
}
