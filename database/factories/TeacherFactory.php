<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Dedication;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->teacher()->create(); // Crear un User relacionado (teacher)

        return [
            'user_id' => $user->id,
            'site_id' => Site::factory(), // Crear un Site relacionado
            'category_id' => Category::factory(), // Crear una Category relacionada
            'dedication_id' => Dedication::factory(), // Crear una Dedication relacionada
            'name' => $this->faker->firstName(),
            'surName' => $this->faker->lastName(),
            'cdi' => $this->faker->unique()->randomNumber(8, true), // Generar cdi único y no nulo
            'genre' => $this->faker->randomElement(['F', 'M']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'birthDate' => $this->faker->date(),
            'datePromotion' => $this->faker->date(),
            'asignaturePromotion' => $this->faker->optional()->word(),
            'has_site' => $this->faker->boolean(),
            'has_category' => $this->faker->boolean(),
            'has_dedication' => $this->faker->boolean(),
            'is_completed' => $this->faker->boolean(),
        ];
    }
}
