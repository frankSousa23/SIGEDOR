<?php

use App\Filament\Pages\Auth\Login;
use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Asegurar roles básicos
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'area_manager']);
    Role::firstOrCreate(['name' => 'teacher']);
});

it('permite login a correos con dominio @sigedor.com', function () {
    $sede = Sede::create(['nombre' => 'Sede Test', 'codigo' => 'TST', 'is_active' => true]);
    $area = Area::create(['nombre' => 'Area Test']);
    $user = User::factory()->create([
        'email' => 'admin@sigedor.com',
        'is_active' => true,
        'is_approved' => true,
        'sede_id' => $sede->id,
        'area_id' => $area->id,
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'email' => 'admin@sigedor.com',
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors();
});

it('rechaza login a correos fuera del dominio @sigedor.com', function () {
    $sede = Sede::create(['nombre' => 'Sede Test 2', 'codigo' => 'TS2', 'is_active' => true]);
    $area = Area::create(['nombre' => 'Area Test 2']);
    $user = User::factory()->create([
        'email' => 'hacker@gmail.com',
        'is_active' => true,
        'is_approved' => true,
        'sede_id' => $sede->id,
        'area_id' => $area->id,
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'email' => 'hacker@gmail.com',
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email' => 'regex']);
});
