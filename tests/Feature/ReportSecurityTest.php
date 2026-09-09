<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_report_automatically_generates_verification_code_and_default_status(): void
    {
        $teacher = Teacher::first();
        $this->assertNotNull($teacher);

        $report = Report::create([
            'teacher_cdi' => $teacher->cdi,
            'memoNumber' => 'MEMO-SEC-001',
            'typeReport' => 'Constancia de Trabajo',
            'report' => 'Certificación laboral institucional.',
            'sede_id' => $teacher->sede_id,
            'area_id' => $teacher->area_id,
        ]);

        $this->assertNotEmpty($report->verification_code);
        $this->assertStringStartsWith('UNERG-REP-', $report->verification_code);
        $this->assertEquals('issued', $report->status);
    }

    public function test_admin_can_view_and_delete_any_report(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $teacher = Teacher::first();
        $report = Report::create([
            'teacher_cdi' => $teacher->cdi,
            'memoNumber' => 'MEMO-ADMIN-01',
            'typeReport' => 'Informe de Escalafón',
            'sede_id' => $teacher->sede_id,
        ]);

        $this->assertTrue(Gate::forUser($admin)->allows('view', $report));
        $this->assertTrue(Gate::forUser($admin)->allows('delete', $report));
    }

    public function test_area_manager_can_only_view_reports_from_same_sede(): void
    {
        $sedes = Sede::take(2)->get();
        $this->assertGreaterThanOrEqual(2, $sedes->count());

        $sede1 = $sedes[0];
        $sede2 = $sedes[1];

        $managerRole = Role::firstOrCreate(['name' => 'area_manager']);
        $areaManager = User::factory()->create(['sede_id' => $sede1->id]);
        $areaManager->assignRole($managerRole);

        $teacher1 = Teacher::where('sede_id', $sede1->id)->first() ?? Teacher::factory()->create(['sede_id' => $sede1->id]);
        $teacher2 = Teacher::where('sede_id', $sede2->id)->first() ?? Teacher::factory()->create(['sede_id' => $sede2->id]);

        $reportSede1 = Report::create([
            'teacher_cdi' => $teacher1->cdi,
            'memoNumber' => 'MEMO-SEDE1',
            'typeReport' => 'Constancia de Trabajo',
            'sede_id' => $sede1->id,
        ]);

        $reportSede2 = Report::create([
            'teacher_cdi' => $teacher2->cdi,
            'memoNumber' => 'MEMO-SEDE2',
            'typeReport' => 'Constancia de Trabajo',
            'sede_id' => $sede2->id,
        ]);

        $this->assertTrue(Gate::forUser($areaManager)->allows('view', $reportSede1));
        $this->assertFalse(Gate::forUser($areaManager)->allows('view', $reportSede2));
        $this->assertFalse(Gate::forUser($areaManager)->allows('delete', $reportSede1));
    }

    public function test_teacher_can_only_view_own_reports_and_cannot_update_or_delete(): void
    {
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);

        $user1 = User::factory()->create();
        $user1->assignRole($teacherRole);

        $user2 = User::factory()->create();
        $user2->assignRole($teacherRole);

        $teacher1 = Teacher::factory()->create(['user_id' => $user1->id]);
        $teacher2 = Teacher::factory()->create(['user_id' => $user2->id]);

        $report1 = Report::create([
            'teacher_cdi' => $teacher1->cdi,
            'memoNumber' => 'MEMO-TEACHER1',
            'typeReport' => 'Constancia de Trabajo',
            'sede_id' => $teacher1->sede_id,
        ]);

        $report2 = Report::create([
            'teacher_cdi' => $teacher2->cdi,
            'memoNumber' => 'MEMO-TEACHER2',
            'typeReport' => 'Constancia de Trabajo',
            'sede_id' => $teacher2->sede_id,
        ]);

        // Docente 1 puede ver su reporte
        $this->assertTrue(Gate::forUser($user1)->allows('view', $report1));
        // Docente 1 NO puede ver el reporte de Docente 2
        $this->assertFalse(Gate::forUser($user1)->allows('view', $report2));

        // Docente NO puede editar ni eliminar reportes
        $this->assertFalse(Gate::forUser($user1)->allows('update', $report1));
        $this->assertFalse(Gate::forUser($user1)->allows('delete', $report1));
    }
}
