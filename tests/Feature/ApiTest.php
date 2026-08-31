<?php

use App\Models\Area;
use App\Models\Category;
use App\Models\Dedication;
use App\Models\Programa;
use App\Models\Report;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->sede = Sede::create(['nombre' => 'Sede Central']);
    $this->area = Area::create(['nombre' => 'Ingeniería']);
    $this->programa = Programa::create(['nombre' => 'Sistemas']);

    $this->user = User::create([
        'name' => 'Docente Test',
        'email' => 'docente@test.com',
        'password' => 'password',
        'sede_id' => $this->sede->id,
        'area_id' => $this->area->id,
        'is_active' => true,
        'is_approved' => true,
    ]);

    $this->teacher = Teacher::create([
        'name' => 'Juan',
        'surName' => 'Pérez',
        'cdi' => '12345678',
        'genre' => 'M',
        'phone' => '+58 123 4567890',
        'email' => $this->user->email,
        'birthDate' => '1980-01-01',
        'user_id' => $this->user->id,
        'sede_id' => $this->sede->id,
        'area_id' => $this->area->id,
        'programa_id' => $this->programa->id,
    ]);

    $this->category = Category::create([
        'teacher_cdi' => $this->teacher->cdi, 
        'instructor' => '2010-01-01'
    ]);

    $this->dedication = new Dedication([
        'teacher_cdi' => $this->teacher->cdi,
        'name' => 'Tiempo Completo',
        'hours' => 36,
    ]);
    $this->dedication->type = 'TC';
    $this->dedication->is_active = true;
    $this->dedication->is_available = true;
    $this->dedication->save();

    $this->teacher->update([
        'category_id' => $this->category->id,
        'dedication_id' => $this->dedication->id
    ]);
});

it('returns a successful response for the teachers API endpoint', function () {
    $response = $this->getJson('/api/v1/teachers');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                 'data' => [
                     'current_page',
                     'data' => [
                         '*' => [
                             'id',
                         ]
                     ]
                 ]
             ]);
});

it('returns a successful response for the reports API endpoint', function () {
    Report::create([
        'teacher_cdi' => $this->teacher->cdi,
        'sede_id' => $this->sede->id,
        'area_id' => $this->area->id,
        'category_id' => $this->category->id,
        'dedication_id' => $this->dedication->id,
        'report' => 'report_file.pdf',
        'memoNumber' => 'MEMO-001',
        'typeReport' => 'Anual',
    ]);

    $response = $this->getJson('/api/v1/reports');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                 'data' => [
                     'current_page',
                     'data' => [
                         '*' => [
                             'id',
                         ]
                     ]
                 ]
             ]);
});
