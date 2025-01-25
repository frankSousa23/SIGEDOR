<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();

            // Títulos académicos
            $table->string('preTitle')->nullable()->comment('Título de pregrado del docente');
            $table->string('lastTitle')->nullable()->comment('Título más alto obtenido');
            $table->boolean('disable_assistant_rule')->default(false)->comment('Habilita promoción inmediata a Asistente');
            $table->string('current_category')->nullable()->comment('Categoría actual del docente')->index();

            // Fechas de categorías
            $table->date('instructor')->nullable()->comment('Fecha de categoría Instructor');
            $table->date('asistente')->nullable()->comment('Fecha de categoría Asistente');
            $table->date('agregado')->nullable()->comment('Fecha de categoría Agregado');
            $table->date('asociado')->nullable()->comment('Fecha de categoría Asociado');
            $table->date('titular')->nullable()->comment('Fecha de categoría Titular');

            // Control de estado y workflow
            $table->boolean('is_active')->default(true)->comment('Estado activo del registro');
            $table->integer('teachers_count')->default(0)->comment('Contador de profesores');
            $table->boolean('is_available')->default(true)->comment('Disponibilidad para asignación');
            $table->text('info')->nullable()->comment('Información adicional');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('teachers')
                  ->onDelete('cascade');

            // Índices
            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
