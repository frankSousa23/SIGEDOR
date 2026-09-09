<?php

use App\Models\Area;
use App\Models\Category;
use App\Models\Dedication;
use App\Models\PermissionTeacher;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Site;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'area_manager', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

    $this->sedeA = Sede::firstOrCreate(['nombre' => 'Sede San Juan']);
    $this->sedeB = Sede::firstOrCreate(['nombre' => 'Sede Calabozo']);

    $this->areaA = Area::firstOrCreate(['nombre' => 'Área de Ingeniería']);
    $this->areaB = Area::firstOrCreate(['nombre' => 'Área de Salud']);

    $this->programaA = Programa::firstOrCreate(['nombre' => 'Ingeniería en Informática']);

    // Admin
    $this->admin = User::factory()->create([
        'email' => 'admin_lifecycle@sigedor.com',
        'sede_id' => $this->sedeA->id,
        'area_id' => $this->areaA->id,
        'is_active' => true,
        'is_approved' => true,
    ]);
    $this->admin->assignRole('admin');

    // Area Manager Sede A
    $this->areaManagerA = User::factory()->create([
        'email' => 'manager_a_lifecycle@sigedor.com',
        'sede_id' => $this->sedeA->id,
        'area_id' => $this->areaA->id,
        'is_active' => true,
        'is_approved' => true,
    ]);
    $this->areaManagerA->assignRole('area_manager');

    // Teacher User Sede A
    $this->teacherUserA = User::factory()->create([
        'name' => 'Carlos Rodriguez',
        'email' => 'carlos_lifecycle@sigedor.com',
        'sede_id' => $this->sedeA->id,
        'area_id' => $this->areaA->id,
        'is_active' => true,
        'is_approved' => true,
    ]);
    $this->teacherUserA->assignRole('teacher');

    $this->teacherA = Teacher::create([
        'cdi' => 'V-99887766',
        'name' => 'Carlos',
        'surName' => 'Rodriguez',
        'genre' => 'M',
        'email' => 'carlos_lifecycle@sigedor.com',
        'user_id' => $this->teacherUserA->id,
        'sede_id' => $this->sedeA->id,
        'area_id' => $this->areaA->id,
        'programa_id' => $this->programaA->id,
    ]);

    // Teacher User Sede B
    $this->teacherUserB = User::factory()->create([
        'name' => 'Maria Perez',
        'email' => 'maria_lifecycle@sigedor.com',
        'sede_id' => $this->sedeB->id,
        'area_id' => $this->areaB->id,
        'is_active' => true,
        'is_approved' => true,
    ]);
    $this->teacherUserB->assignRole('teacher');

    $this->teacherB = Teacher::create([
        'cdi' => 'V-99887755',
        'name' => 'Maria',
        'surName' => 'Perez',
        'genre' => 'F',
        'email' => 'maria_lifecycle@sigedor.com',
        'user_id' => $this->teacherUserB->id,
        'sede_id' => $this->sedeB->id,
        'area_id' => $this->areaB->id,
    ]);
});

test('teacher policy accurately validates ownership and sede boundaries', function () {
    // Admin can view both
    expect(Gate::forUser($this->admin)->allows('view', $this->teacherA))->toBeTrue();
    expect(Gate::forUser($this->admin)->allows('view', $this->teacherB))->toBeTrue();

    // Area Manager A can view Teacher A, but not Teacher B (different sede/area)
    expect(Gate::forUser($this->areaManagerA)->allows('view', $this->teacherA))->toBeTrue();
    expect(Gate::forUser($this->areaManagerA)->allows('view', $this->teacherB))->toBeFalse();

    // Teacher A can view own profile via linked cdi / user_id
    expect(Gate::forUser($this->teacherUserA)->allows('view', $this->teacherA))->toBeTrue();
    expect(Gate::forUser($this->teacherUserA)->allows('view', $this->teacherB))->toBeFalse();

    // Teacher A update own profile allowed, but cannot update Teacher B
    expect(Gate::forUser($this->teacherUserA)->allows('update', $this->teacherA))->toBeTrue();
    expect(Gate::forUser($this->teacherUserA)->allows('update', $this->teacherB))->toBeFalse();
});

test('category policy handles nullsafe relations and allows area manager to create', function () {
    expect(Gate::forUser($this->areaManagerA)->allows('create', Category::class))->toBeTrue();
    expect(Gate::forUser($this->admin)->allows('create', Category::class))->toBeTrue();
    expect(Gate::forUser($this->teacherUserA)->allows('create', Category::class))->toBeFalse();
});

test('category creation automatically synchronizes teacher category_id', function () {
    expect($this->teacherA->category_id)->toBeNull();

    $category = Category::create([
        'teacher_cdi' => $this->teacherA->cdi,
        'current_category' => 'Asistente',
        'asistente' => '2026-01-01',
        'preTitle' => 'Ingeniero en Computación',
        'lastTitle' => 'Magíster en Sistemas',
    ]);

    $this->teacherA->refresh();
    expect($this->teacherA->category_id)->toBe($category->id);
    expect($this->teacherA->category?->current_category)->toBe('Asistente');

    // Deleting category should reset category_id
    $category->delete();
    $this->teacherA->refresh();
    expect($this->teacherA->category_id)->toBeNull();
});

test('dedication creation automatically synchronizes teacher dedication_id', function () {
    expect($this->teacherA->dedication_id)->toBeNull();

    $dedication = Dedication::create([
        'teacher_cdi' => $this->teacherA->cdi,
        'name' => 'Tiempo Completo',
        'hours' => 30,
    ]);

    $this->teacherA->refresh();
    expect($this->teacherA->dedication_id)->toBe($dedication->id);
    expect($this->teacherA->dedication?->name)->toBe('Tiempo Completo');
    expect($dedication->type)->toBe('TC');

    // Deleting dedication should reset dedication_id
    $dedication->delete();
    $this->teacherA->refresh();
    expect($this->teacherA->dedication_id)->toBeNull();
});

test('site creation automatically synchronizes teacher site_id and territorial assignment', function () {
    expect($this->teacherA->site_id)->toBeNull();

    $site = Site::create([
        'teacher_cdi' => $this->teacherA->cdi,
        'sede_id' => $this->sedeA->id,
        'area_id' => $this->areaA->id,
        'programa_id' => $this->programaA->id,
        'uc' => 4,
        'weekHours' => 8,
        'sections' => 2,
    ]);

    $this->teacherA->refresh();
    expect($this->teacherA->site_id)->toBe($site->id);

    // Deleting site should reset site_id
    $site->delete();
    $this->teacherA->refresh();
    expect($this->teacherA->site_id)->toBeNull();
});

test('permission teacher auto-generates name and calculates end date', function () {
    $permission = PermissionTeacher::create([
        'teacher_cdi' => $this->teacherA->cdi,
        'memo_number' => 'MEMO-TEST-LIFECYCLE-01',
        'type' => 'Año Sabático',
        'duration_type' => 'semestral',
        'start_date' => '2026-01-15',
        'status' => 'pending',
    ]);

    expect($permission->name)->not->toBeEmpty();
    expect($permission->name)->toContain('Año Sabático');

    $calculatedEnd = $permission->calculateEndDate();
    expect($calculatedEnd)->toBe('2026-07-15');
});

test('dedication hours normative validation enforces unerg statutes', function () {
    $validHoursTC = Dedication::getValidHours('Tiempo Completo');
    expect($validHoursTC)->toHaveKey(30);
    expect($validHoursTC)->not->toHaveKey(15);

    $validHoursEX = Dedication::getValidHours('Exclusiva');
    expect($validHoursEX)->toHaveKey(35);
    expect($validHoursEX)->toHaveKey(36);
    expect($validHoursEX)->not->toHaveKey(40);
});
