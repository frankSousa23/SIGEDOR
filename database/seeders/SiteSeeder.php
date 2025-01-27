<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use FakerPHP\Generator as Faker;
use Illuminate\Support\Facades\Schema;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar restricciones de clave foránea
        Schema::disableForeignKeyConstraints();

        // Truncar la tabla sites
        Site::truncate();

        // Habilitar restricciones de clave foránea
        Schema::enableForeignKeyConstraints();

        // Insertar todas las opciones de SITES con un área predeterminada
        $sites = [];
        foreach (Site::SITES as $site) {
            $sites[] = ['name' => $site, 'area' => 'Área predeterminada'];
        }

        foreach ($sites as $site) {
            Site::create($site);
        }

        // Insertar todas las opciones de AREAS
        $areas = [];
        foreach (Site::AREAS as $area) {
            $areas[] = ['name' => 'Área ' . $area, 'area' => $area];
        }

        foreach ($areas as $area) {
            Site::create($area);
        }
    }
}
