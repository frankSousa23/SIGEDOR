<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Dedication;
use App\Models\Site;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_teachers_have_categories_dedications_and_sites_linked(): void
    {
        $teachers = Teacher::all();

        $this->assertCount(25, $teachers);
        $this->assertEquals(25, Category::count());
        $this->assertEquals(25, Dedication::count());
        $this->assertEquals(25, Site::count());

        foreach ($teachers as $teacher) {
            $this->assertNotNull($teacher->category, "Teacher CDI {$teacher->cdi} has no linked Category.");
            $this->assertNotNull($teacher->dedication, "Teacher CDI {$teacher->cdi} has no linked Dedication.");
            $this->assertNotNull($teacher->sede, "Teacher CDI {$teacher->cdi} has no linked Sede.");
            $this->assertNotNull($teacher->area, "Teacher CDI {$teacher->cdi} has no linked Area.");

            // Validar que el correo del docente pertenezca a @sigedor.com
            $this->assertStringEndsWith('@sigedor.com', $teacher->email);
        }
    }

    public function test_all_seeded_users_belong_to_sigedor_domain(): void
    {
        $users = User::all();
        $this->assertNotEmpty($users);

        foreach ($users as $user) {
            $this->assertStringEndsWith('@sigedor.com', $user->email);
        }
    }
}
