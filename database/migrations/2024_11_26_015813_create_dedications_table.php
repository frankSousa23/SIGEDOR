<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dedications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['TCV', 'MT', 'TC', 'EX'])->comment('TCV: Tiempo Convencional, MT: Medio Tiempo, TC: Tiempo Completo, EX: Exclusiva');
            $table->integer('hours')->comment('Horas semanales de dedicación');
            $table->enum('director_role', ['Coordinador', 'Jefe de Departamento', 'Decano'])->nullable();
            $table->integer('max_students')->nullable()->unsigned()->comment('Número máximo de estudiantes en asesoría');
            $table->integer('min_advisory_hours')->nullable()->unsigned()->comment('Horas mínimas dedicadas a asesorías');
            $table->text('description')->nullable();

            // Control de estado
            $table->boolean('is_active')->default(true);
            $table->integer('teachers_count')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamp('last_assignment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dedications');
    }
};
