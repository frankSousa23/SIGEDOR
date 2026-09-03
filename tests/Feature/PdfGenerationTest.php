<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Dedication;
use App\Models\PermissionTeacher;
use App\Models\Report;
use App\Models\Site;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_individual_teacher_pdf_renders_successfully(): void
    {
        $teacher = Teacher::with(['sede', 'area', 'programa', 'category', 'dedication'])->first();
        $this->assertNotNull($teacher);

        $pdf = Pdf::loadView('pdf.teacher-individual', ['teacher' => $teacher]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_teachers_bulk_pdf_renders_successfully(): void
    {
        $teachers = Teacher::take(10)->get();
        $this->assertNotEmpty($teachers);

        $pdf = Pdf::loadView('pdf.teachers', ['teachers' => $teachers]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_category_pdf_renders_successfully(): void
    {
        $category = Category::with('teacher')->first();
        $this->assertNotNull($category);

        $pdf = Pdf::loadView('pdf.category', ['category' => $category]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_dedication_pdf_renders_successfully(): void
    {
        $dedication = Dedication::with('teacher')->first();
        $this->assertNotNull($dedication);

        $pdf = Pdf::loadView('pdf.dedication', ['dedication' => $dedication]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_site_pdf_renders_successfully(): void
    {
        $site = Site::with(['teacher', 'programa'])->first();
        $this->assertNotNull($site);

        $pdf = Pdf::loadView('pdf.site', ['site' => $site]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_report_pdf_renders_successfully(): void
    {
        $teacher = Teacher::first();

        $report = Report::create([
            'teacher_cdi' => $teacher->cdi,
            'memoNumber' => 'MEMO-TEST-001',
            'typeReport' => 'Dictamen Académico',
            'report' => 'Dictamen con texto largo para probar la emisión de documento sin errores.',
            'sede_id' => $teacher->sede_id,
            'area_id' => $teacher->area_id,
            'category_id' => $teacher->category_id,
            'dedication_id' => $teacher->dedication_id,
        ]);

        $pdf = Pdf::loadView('pdf.report', ['report' => $report]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_reports_bulk_pdf_renders_successfully(): void
    {
        $teacher = Teacher::first();

        $report = Report::create([
            'teacher_cdi' => $teacher->cdi,
            'memoNumber' => 'MEMO-TEST-002',
            'typeReport' => 'Constancia',
            'report' => 'Reporte general de prueba',
            'sede_id' => $teacher->sede_id,
            'area_id' => $teacher->area_id,
            'category_id' => $teacher->category_id,
            'dedication_id' => $teacher->dedication_id,
        ]);

        $pdf = Pdf::loadView('pdf.reports', ['reports' => collect([$report])]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }

    public function test_permission_pdf_renders_successfully(): void
    {
        $teacher = Teacher::first();

        $permission = PermissionTeacher::create([
            'teacher_cdi' => $teacher->cdi,
            'memo_number' => 'MEMO-PERM-TEST',
            'type' => 'Año Sabático',
            'status' => 'approved',
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
            'duration_type' => 'semestral',
            'name' => 'Investigación Sabática',
            'description' => 'Descripción del permiso de investigación.',
        ]);

        $pdf = Pdf::loadView('pdf.permission', ['permission' => $permission]);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF-', $output);
    }
}
