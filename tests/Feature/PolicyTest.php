<?php

use App\Models\Area;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'area_manager']);
    Role::firstOrCreate(['name' => 'teacher']);
});

it('admin puede ver cualquier teacher', function () {
    $sede = Sede::create(['nombre' => 'Sede Admin', 'codigo' => 'SA', 'is_active' => true]);
    $area = Area::create(['nombre' => 'Area Admin']);

    $admin = User::factory()->admin()->create([
        'sede_id' => $sede->id,
        'area_id' => $area->id,
    ]);
    $teacher = Teacher::factory()->create([
        'sede_id' => $sede->id,
    ]);

    expect($admin->can('view', $teacher))->toBeTrue();
    expect($admin->can('update', $teacher))->toBeTrue();
});

it('jefe de area solo puede ver teachers de su sede', function () {
    $sede1 = Sede::create(['nombre' => 'Sede 1', 'codigo' => 'S1']);
    $sede2 = Sede::create(['nombre' => 'Sede 2', 'codigo' => 'S2']);
    $area = Area::create(['nombre' => 'Area 1']);

    $manager = User::factory()->areaManager()->create([
        'sede_id' => $sede1->id,
        'area_id' => $area->id,
    ]);

    $teacher1 = Teacher::factory()->create([
        'sede_id' => $sede1->id,
    ]);

    $teacher2 = Teacher::factory()->create([
        'sede_id' => $sede2->id,
    ]);

    expect($manager->can('view', $teacher1))->toBeTrue();
    expect($manager->can('view', $teacher2))->toBeFalse();
});
