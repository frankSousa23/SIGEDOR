<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Site.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'teacher_cdi' => (string) $this->faker->unique()->numberBetween(10000000, 99999999),
            'sede_id' => Sede::inRandomOrder()->first()?->id,
            'area_id' => Area::inRandomOrder()->first()?->id,
            'programa_id' => Programa::inRandomOrder()->first()?->id,
            'uc' => $this->faker->numberBetween(2, 5),
            'weekHours' => $this->faker->numberBetween(4, 12),
            'sections' => $this->faker->numberBetween(1, 4),
            'info' => $this->faker->optional()->sentence(),
            'is_active' => true,
            'is_available' => true,
        ];
    }
}
