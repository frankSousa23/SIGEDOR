<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
namespace Database\Seeders;
use App\Models\Sede;

class SiteSeeder extends Seeder
{
    public function run()
{
    $sedes = \App\Models\Sede::all();

    foreach($sedes as $sede){
        \App\Models\Site::create([
            'sede_id' => $sede->id,
            'name' => $sede->nombre.' - Campus Principal',
            'uc' => 'Matemáticas Básicas',
            'weekHours' => 20,
            // ... otros campos requeridos ...
        ]);
    }
}
}
