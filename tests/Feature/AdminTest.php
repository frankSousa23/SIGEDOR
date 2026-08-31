<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de acceso y funcionalidad del Administrador.
 *
 * Verifica que el rol `admin` puede acceder correctamente a las secciones
 * de gestión de usuarios del panel Filament.
 */
class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_ver_listado_de_usuarios()
    {
        $this->seed(RoleSeeder::class);
        $sede = Sede::create(['nombre' => 'Sede Central']);
        $area = Area::create(['nombre' => 'Sistemas']);

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => 'password',
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin);
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
    }
}
