<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\SiteOption;
use App\Models\Role;
use Database\Seeders\RoleSeeder;

class AdminTest extends TestCase
{
    public function test_admin_puede_ver_todos_los_usuarios()
    {
        $this->seed(RoleSeeder::class);

        $siteOption = SiteOption::factory()->create();
        $role = Role::where('name', 'admin')->first();

        $admin = User::factory()->create([
            'site_option_id' => $siteOption->id,
            'role_id' => $role->id,
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin);
        $response = $this->get('/filament/dashboard/users'); // Usar ruta directa
        $response->assertStatus(200);
    }
}
