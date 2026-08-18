<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory para el modelo User.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'is_active' => true,
            'is_approved' => true,
            'sede_id' => Sede::inRandomOrder()->first()?->id,
            'area_id' => Area::inRandomOrder()->first()?->id,
        ];
    }

    /**
     * Estado con rol Administrador.
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('admin');
        });
    }

    /**
     * Estado con rol Jefe de Área.
     */
    public function areaManager(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('area_manager');
        });
    }

    /**
     * Estado con rol Docente.
     */
    public function teacher(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('teacher');
        });
    }
}
