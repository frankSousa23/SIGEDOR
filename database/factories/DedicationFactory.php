<?php

namespace Database\Factories;

use App\Models\Dedication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Dedication.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dedication>
 */
class DedicationFactory extends Factory
{
    protected $model = Dedication::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['Tiempo Convencional', 'Medio Tiempo', 'Tiempo Completo', 'Exclusiva']);
        $hours = match ($type) {
            'Tiempo Convencional' => 12,
            'Medio Tiempo' => 18,
            'Tiempo Completo' => 30,
            'Exclusiva' => 36,
        };

        return [
            'teacher_cdi' => (string) $this->faker->unique()->numberBetween(10000000, 99999999),
            'name' => $type,
            'hours' => $hours,
            'director' => $this->faker->optional(0.3)->randomElement(['Coordinador', 'Jefe de Departamento', 'Decano', 'Director']),
            'studentNumber' => $this->faker->numberBetween(20, 60),
            'studentHours' => $this->faker->numberBetween(4, 16),
            'info' => $this->faker->optional()->sentence(),
        ];
    }
}
