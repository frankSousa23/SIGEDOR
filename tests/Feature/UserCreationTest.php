<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de creación y asignación de roles de Usuario.
 *
 * Verifica que los usuarios pueden ser creados con los datos correctos
 * y que la asignación del rol `teacher` funciona a través de Spatie Permission.
 */
class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_de_usuario_con_roles()
    {
        $this->seed(RoleSeeder::class);
        $sede = Sede::create(['nombre' => 'Sede Central']);
        $area = Area::create(['nombre' => 'Ingeniería']);

        $user = User::create([
            'name' => 'Profesor Nuevo',
            'email' => 'nuevo@sigedor.com',
            'password' => 'password',
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $user->assignRole('teacher');

        $this->assertDatabaseHas('users', ['email' => 'nuevo@sigedor.com']);
        $this->assertTrue($user->hasRole('teacher'));
    }
}
