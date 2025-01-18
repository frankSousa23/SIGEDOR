<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            'cdi' => $this->faker->unique()->numerify('########'),
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => true,
            'is_approved' => true,
            'site_id' => null, // Se asignará después
            'remember_token' => Str::random(10),
        ];
    }

    public function teacher()
    {
        return $this->state(function (array $attributes) {
            return $attributes;
        })->afterCreating(function (User $user) {
            $user->assignRole('teacher');
        });
    }

    public function areaManager()
    {
        return $this->state(function (array $attributes) {
            return $attributes;
        })->afterCreating(function (User $user) {
            $user->assignRole('area_manager');
        });
    }
}
