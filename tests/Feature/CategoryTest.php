<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Models\Area;
use App\Models\Category;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_calculo_automatico_de_categoria_actual()
    {
        $category = new Category([
            'teacher_cdi' => '10000001',
            'instructor' => '2010-01-01',
            'asistente' => '2013-01-01',
            'agregado' => '2016-01-01',
            'asociado' => '2019-01-01',
            'titular' => '2022-01-01',
        ]);

        $this->assertEquals('Titular', $category->current_category);
    }

    public function test_direct_promotion_specialty_master()
    {
        $sede = Sede::create(['nombre' => 'Test Sede']);
        $area = Area::create(['nombre' => 'Test Area']);

        $admin = User::factory()->create([
            'sede_id' => $sede->id,
            'area_id' => $area->id,
        ]);
        $admin->assignRole('admin');

        $teacher = Teacher::factory()->create([
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'user_id' => User::factory()->create([
                'sede_id' => $sede->id,
                'area_id' => $area->id,
            ])->id,
        ]);

        Livewire::actingAs($admin)
            ->test(CreateCategory::class)
            ->fillForm([
                'teacher_cdi' => $teacher->cdi,
                'current_category' => 'Asistente',
                'direct_promotion_rule' => 'specialty_master',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::where('teacher_cdi', $teacher->cdi)->first();
        $this->assertNotNull($category);
        $this->assertEquals(now()->toDateString(), substr($category->instructor, 0, 10));
        $this->assertEquals(now()->toDateString(), substr($category->asistente, 0, 10));
        $this->assertNull($category->agregado);
    }

    public function test_direct_promotion_doctorate()
    {
        $sede = Sede::create(['nombre' => 'Test Sede 2']);
        $area = Area::create(['nombre' => 'Test Area 2']);

        $admin = User::factory()->create([
            'sede_id' => $sede->id,
            'area_id' => $area->id,
        ]);
        $admin->assignRole('admin');

        $teacher = Teacher::factory()->create([
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'user_id' => User::factory()->create([
                'sede_id' => $sede->id,
                'area_id' => $area->id,
            ])->id,
        ]);

        Livewire::actingAs($admin)
            ->test(CreateCategory::class)
            ->fillForm([
                'teacher_cdi' => $teacher->cdi,
                'current_category' => 'Agregado',
                'direct_promotion_rule' => 'doctorate',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::where('teacher_cdi', $teacher->cdi)->first();
        $this->assertNotNull($category);
        $this->assertEquals(now()->toDateString(), substr($category->instructor, 0, 10));
        $this->assertEquals(now()->toDateString(), substr($category->asistente, 0, 10));
        $this->assertEquals(now()->toDateString(), substr($category->agregado, 0, 10));
        $this->assertNull($category->asociado);
    }
}
