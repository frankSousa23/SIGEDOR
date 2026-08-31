<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areas = [
            'Ciencias de la educación',
            'Ciencias de la salud',
            'Ciencias económicas y sociales',
            'Ciencias odontológicas',
            'Ciencias políticas y jurídicas',
            'Estudios continuos',
            'Humanidades, letras y artes',
            'Ingeniería agronómica',
            'Ingeniería de sistemas',
            'Ingeniería, arquitectura y tecnología',
            'Medicina veterinaria',
            'Post-grado',
            'Programa nacional de formación',
        ];

        foreach ($areas as $nombre) {
            Area::firstOrCreate(
                ['nombre' => $nombre]
            );
        }
    }
}
