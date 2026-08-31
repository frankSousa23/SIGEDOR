<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Teacher.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        $user = User::factory()->teacher()->create();

        return [
            'user_id' => $user->id,
            'sede_id' => Sede::inRandomOrder()->first()?->id,
            'area_id' => Area::inRandomOrder()->first()?->id,
            'programa_id' => Programa::inRandomOrder()->first()?->id,
            'name' => $this->faker->firstName(),
            'surName' => $this->faker->lastName(),
            'cdi' => (string) $this->faker->unique()->numberBetween(10000000, 99999999),
            'genre' => $this->faker->randomElement(['F', 'M']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $user->email,
            'birthDate' => $this->faker->date('Y-m-d', '-30 years'),
            'datePromotion' => $this->faker->date('Y-m-d', '-5 years'),
            'asignaturePromotion' => $this->faker->words(3, true),
        ];
    }
}
