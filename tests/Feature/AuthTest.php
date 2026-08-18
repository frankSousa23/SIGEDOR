<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeText('SIGEDOR');
    }

    public function test_admin_can_access_admin_panel()
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
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    public function test_inactive_user_cannot_access_panel()
    {
        $this->seed(RoleSeeder::class);
        $sede = Sede::create(['nombre' => 'Sede Central']);
        $area = Area::create(['nombre' => 'Sistemas']);

        $inactiveUser = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@test.com',
            'password' => 'password',
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'is_active' => false,
            'is_approved' => false,
        ]);
        $inactiveUser->assignRole('teacher');

        $this->actingAs($inactiveUser);
        $response = $this->get('/admin');
        $response->assertStatus(403);
    }
}
