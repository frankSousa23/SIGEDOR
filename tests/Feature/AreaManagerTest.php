<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AreaManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_gestor_solo_ve_usuarios_de_su_area()
    {
        $gestor = User::role('area_manager')->first();
        $this->actingAs($gestor);

        $response = $this->get('/filament/dashboard/teachers');
        $response->assertSeeText('Gestión de Área');
    }
}
