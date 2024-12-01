<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dedications', function (Blueprint $table) {
            $table->id();
            $table->enum('dedication', ['TCV', 'MT', 'TC', 'EX'])->comment('TCV: Tiempo Convencional, MT: Medio Tiempo, TC: Tiempo Completo, EX: Exclusiva');
            $table->integer('hours')->comment('Horas semanales de dedicación');
            $table->enum('director', ['Coordinador', 'Jefe de Departamento', 'Decano'])->nullable();
            $table->integer('studentNumber')->nullable()->unsigned()->comment('Número de estudiantes en asesoría');
            $table->integer('studentHours')->nullable()->unsigned()->comment('Horas dedicadas a asesorías');
            $table->text('info')->nullable()->comment('Observaciones adicionales');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->unique('teacher_id', 'teacher_single_dedication');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dedications');
    }
};
