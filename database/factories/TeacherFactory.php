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
        $user = User::factory()->teacher()->make();
        $user->save();

        return [
            'user_id' => $user->id,
            'site_id' => Site::inRandomOrder()->first()->id ?? Site::factory()->create()->id,
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory()->create()->id,
            'dedication_id' => Dedication::inRandomOrder()->first()->id ?? Dedication::factory()->create()->id,
            'name' => $this->faker->firstName(),
            'surName' => $this->faker->lastName(),
            'cdi' => $this->faker->unique()->randomNumber(5, true), // Reducir a 5 dígitos
            'genre' => $this->faker->randomElement(['F', 'M']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'birthDate' => $this->faker->date(),
            'datePromotion' => $this->faker->date(),
            'asignaturePromotion' => $this->faker->optional()->word(),
            'has_site' => true, // Siempre tiene sede para pruebas
            'has_category' => true, // Siempre tiene categoría para pruebas
            'has_dedication' => true, // Siempre tiene dedicación para pruebas
            'is_completed' => true, // Siempre completado para pruebas
        ];
    }
}
