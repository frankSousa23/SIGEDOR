<?php

namespace Database\Factories;

use App\Models\Dedication;
use Illuminate\Database\Eloquent\Factories\Factory;

class DedicationFactory extends Factory
{
    protected $model = Dedication::class;

    public function definition(): array
    {
        static $dedicationIndex = 0;
        $dedicationTypes = [
            Dedication::DEDICATION_TCV . '_1',
            Dedication::DEDICATION_TCV . '_2',
            Dedication::DEDICATION_TCV . '_3',
            Dedication::DEDICATION_MT . '_1',
            Dedication::DEDICATION_MT . '_2',
            Dedication::DEDICATION_TC . '_1',
            Dedication::DEDICATION_TC . '_2',
            Dedication::DEDICATION_EX . '_1',
            Dedication::DEDICATION_EX . '_2',
        ];

        $dedicationType = $dedicationTypes[$dedicationIndex % count($dedicationTypes)];
        $dedicationIndex++;

        $baseType = explode('_', $dedicationType)[0];
        $hours = match ($baseType) {
            Dedication::DEDICATION_TCV => $this->faker->numberBetween(1, 17),
            Dedication::DEDICATION_MT => 18,
            Dedication::DEDICATION_TC => 30,
            Dedication::DEDICATION_EX => $this->faker->randomElement([35, 36]),
        };

        return [
            'name' => $dedicationType,
            'hours' => $hours,
            'director' => $this->faker->randomElement([
                Dedication::DIRECTOR_COORDINATOR,
                Dedication::DIRECTOR_DEPARTMENT_HEAD,
                Dedication::DIRECTOR_DEAN,
                Dedication::DIRECTOR_DIRECTOR,
                Dedication::DIRECTOR_SUB_DIRECTOR,
                null
            ]),
            'studentNumber' => $this->faker->numberBetween(0, 30),
            'studentHours' => $this->faker->numberBetween(0, 10),
        ];
    }
}
