<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Category.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'teacher_cdi' => (string) $this->faker->unique()->numberBetween(10000000, 99999999),
            'preTitle' => 'Licenciado en Educación',
            'lastTitle' => 'Magíster en Docencia Universitaria',
            'disable_assistant_rule' => false,
            'current_category' => $this->faker->randomElement(['Instructor', 'Asistente', 'Agregado', 'Asociado', 'Titular']),
            'instructor' => $this->faker->date('Y-m-d', '-10 years'),
            'asistente' => $this->faker->optional(0.7)->date('Y-m-d', '-7 years'),
            'agregado' => $this->faker->optional(0.5)->date('Y-m-d', '-4 years'),
            'asociado' => $this->faker->optional(0.3)->date('Y-m-d', '-2 years'),
            'titular' => $this->faker->optional(0.1)->date('Y-m-d', '-1 years'),
            'info' => $this->faker->optional()->sentence(),
        ];
    }
}
