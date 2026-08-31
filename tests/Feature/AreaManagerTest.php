<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de acceso y funcionalidad del Jefe de Área.
 *
 * Verifica que el rol `area_manager` puede acceder al panel Filament
 * y que las relaciones con permisos y dedicaciones funcionen correctamente.
 */
class AreaManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_jefe_de_area_puede_acceder_al_panel()
    {
        $this->seed(RoleSeeder::class);
        $sede = Sede::create(['nombre' => 'Sede Central']);
        $area = Area::create(['nombre' => 'Ciencias de la Salud']);

        $manager = User::create([
            'name' => 'Jefe de Area Test',
            'email' => 'manager@test.com',
            'password' => 'password',
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $manager->assignRole('area_manager');

        $this->actingAs($manager);
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }
}
