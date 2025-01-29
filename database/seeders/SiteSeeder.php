<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use FakerPHP\Generator as Faker;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteOption;
use App\Models\AreaOption;

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

        // Crear opciones PRIMERO
        $siteOption = SiteOption::firstOrCreate(['name' => 'Acarigua/Portuguesa']);
        $areaOption = AreaOption::firstOrCreate(['name' => 'Área predeterminada']);

        // Crear site usando las columnas CORRECTAS
        Site::firstOrCreate(
            ['name' => 'Acarigua/Portuguesa'],  // Buscar por nombre único
            [
                'site_option_id' => $siteOption->id,
                'area_id' => $areaOption->id,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Insertar todas las opciones de SITES con un área predeterminada
        $sites = [];
        foreach (Site::SITES as $site) {
            $sites[] = [
                'name' => $site,
                'site_option_id' => $siteOption->id,
                'area_id' => $areaOption->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach ($sites as $site) {
            Site::firstOrCreate(
                ['name' => $site['name']],  // Buscar por nombre único
                $site
            );
        }

        // Insertar todas las opciones de AREAS
        $areas = [];
        foreach (Site::AREAS as $area) {
            $areas[] = [
                'name' => 'Área ' . $area,
                'site_option_id' => $siteOption->id,
                'area_id' => $areaOption->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach ($areas as $area) {
            Site::firstOrCreate(
                ['name' => $area['name']],  // Buscar por nombre único
                $area
            );
        }
    }
}
