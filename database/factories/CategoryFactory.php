<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'preTitle' => $this->faker->randomElement(['Ingeniero', 'Licenciado', 'Profesor']),
            'lastTitle' => $this->faker->randomElement(['Magister', 'Doctor', 'Especialista']),
            'disable_assistant_rule' => false,
            'current_category' => 'Instructor',
            'instructor' => now(),
            'asistente' => null,
            'agregado' => null,
            'asociado' => null,
            'titular' => null,
            'is_active' => true,
            'is_available' => true,
            'teachers_count' => 0,
            'info' => null
        ];
    }
}
