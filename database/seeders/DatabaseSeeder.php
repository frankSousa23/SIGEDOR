<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Dedication;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Seeders básicos
        $this->call([
            SiteSeeder::class,
            UserSeeder::class,
        ]);

        // Crear 10 sites adicionales
        $sites = Site::factory(10)->create();

        // Crear todas las dedicaciones posibles primero
        $dedications = collect([
            'TCV_1', 'TCV_2', 'TCV_3',
            'MT_1', 'MT_2',
            'TC_1', 'TC_2',
            'EX_1', 'EX_2'
        ])->map(function ($name) {
            $baseType = explode('_', $name)[0];
            $hours = match ($baseType) {
                'TCV' => rand(1, 17),
                'MT' => 18,
                'TC' => 30,
                'EX' => rand(35, 36),
            };

            return Dedication::create([
                'name' => $name,
                'hours' => $hours,
                'director' => null,
                'studentNumber' => rand(0, 30),
                'studentHours' => rand(0, 10),
            ]);
        });

        // Crear 90 profesores
        User::factory(90)
            ->teacher()
            ->create()
            ->each(function ($user) use ($sites, $faker, $dedications) {
                // Separar el nombre completo en nombre y apellido
                $nameParts = explode(' ', $user->name);
                $firstName = $nameParts[0];
                $lastName = count($nameParts) > 1 ? $nameParts[1] : $faker->lastName;

                // Crear registro de Teacher con todos los campos requeridos y únicos
                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'site_id' => $sites->random()->id,
                    'name' => $firstName,
                    'surName' => $lastName,
                    'cdi' => $user->cdi,
                    'email' => $user->email,
                    'phone' => $faker->numerify('####-#######'),
                    'birthDate' => $faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                    'datePromotion' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                ]);

                // Crear categoría para el Teacher
                Category::factory()->create([
                    'teacher_id' => $teacher->id
                ]);

                // Asignar una dedicación existente al Teacher
                $dedication = $dedications->random();
                $dedication->teacher_id = $teacher->id;
                $dedication->save();
            });

        // Crear 10 jefes de área
        User::factory(10)
            ->areaManager()
            ->create()
            ->each(function ($user) use ($sites, $faker) {
                // Separar el nombre completo en nombre y apellido
                $nameParts = explode(' ', $user->name);
                $firstName = $nameParts[0];
                $lastName = count($nameParts) > 1 ? $nameParts[1] : $faker->lastName;

                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'site_id' => $sites->random()->id,
                    'name' => $firstName,
                    'surName' => $lastName,
                    'cdi' => $user->cdi,
                    'email' => $user->email,
                    'phone' => $faker->numerify('####-#######'),
                    'birthDate' => $faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                    'datePromotion' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                ]);
            });
    }
}
