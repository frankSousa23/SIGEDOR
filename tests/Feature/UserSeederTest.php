<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_user_per_role()
    {
        // Ejecutar el seeder de usuarios
        $this->seed(\Database\Seeders\UserSeeder::class);

        // Roles esperados según la opción utilizada en el seeder
        $rolesEsperados = ['admin', 'area_manager', 'teacher'];

        // Verificar que para cada rol exista un usuario asignado
        foreach ($rolesEsperados as $roleName) {
            // Se espera que el email se genere con un dominio genérico (@ejemplo.com)
            $this->assertDatabaseHas('users', ['email' => $roleName . '@ejemplo.com']);

            $user = User::where('email', $roleName . '@ejemplo.com')->first();
            $this->assertNotNull($user, "El usuario para el rol $roleName no fue encontrado");

            // Comprueba que el usuario tenga asignado el rol correspondiente
            $this->assertTrue(
                $user->hasRole($roleName),
                "El usuario con email {$user->email} no tiene el rol asignado $roleName"
            );
        }
    }
}
