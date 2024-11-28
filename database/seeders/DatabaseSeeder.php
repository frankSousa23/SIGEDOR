<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);

        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('teachers')->insert([
            'cdi' => '1565973',
            'name' => 'Jesús',
            'surName' => 'Rivas',
            'genre' => 'M',
            'phone' => '04142375006',
            'email' => 'jesus.rivastortosa875@gmail.com',
            'birthDate' => '1950/09/15',
            'datePromotion' => '2004/12/15',
            'asignaturePromotion' => 'Comunitaria',

        ]);

        DB::table('teachers')->insert([
            'cdi' => '2509861',
            'name' => 'Misael',
            'surName' => 'Pérez',
            'genre' => 'M',
            'phone' => '04144650186',
            'email' => 'misaelperezgomez@hotmail.com',
            'birthDate' => '1946/01/14',
            'datePromotion' => '2011/12/12',
            'asignaturePromotion' => 'Legislación Mercantil',

        ]);DB::table('teachers')->insert([
            'cdi' => '2514145',
            'name' => 'Luz',
            'surName' => 'Diaz',
            'genre' => 'F',
            'phone' => '04265425923',
            'email' => 'luzadiaz05@hotmail.com',
            'birthDate' => '1948/05/28',
            'datePromotion' => '2002/03/18',
            'asignaturePromotion' => 'Lenguaje y Comunicación',

        ]);DB::table('teachers')->insert([
            'cdi' => '2516924',
            'name' => 'Lilia',
            'surName' => 'Bastidas',
            'genre' => 'F',
            'phone' => '04144650371',
            'email' => 'liliabastidasr@gmail.com',
            'birthDate' => '1948/01/19',
            'datePromotion' => '2000/05/30',
            'asignaturePromotion' => 'Derecho',

        ]);DB::table('teachers')->insert([
            'cdi' => '2517421',
            'name' => 'Pedro',
            'surName' => 'Gomez',
            'genre' => 'M',
            'phone' => '04149443137',
            'email' => 'pegom56@gmail.com',
            'birthDate' => '1949/10/11',
            'datePromotion' => '2002/12/09',
            'asignaturePromotion' => 'Bioquímica',

        ]);

    }
}
