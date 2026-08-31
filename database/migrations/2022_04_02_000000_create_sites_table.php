<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();

            // Relación docente (formato corregido)
            $table->string('teacher_cdi', 15)->comment('CDI docente');
            $table->foreign('teacher_cdi', 'fk_sites_teacher_cdi')
                ->references('cdi')
                ->on('teachers')
                ->onDelete('cascade');

            // Relaciones institucionales (nombres explícitos)
            $table->foreignId('sede_id')
                ->constrained('sedes', 'id', 'fk_sites_sede_id')
                ->onDelete('cascade')
                ->nullable();

            $table->foreignId('area_id')
                ->constrained('areas', 'id', 'fk_sites_area_id')
                ->onDelete('cascade')
                ->nullable();

            $table->foreignId('programa_id')
                ->constrained('programas', 'id', 'fk_sites_programa_id')
                ->onDelete('cascade')
                ->nullable();

            // Campos principales
            $table->string('uc', 100)->nullable();
            $table->unsignedSmallInteger('weekHours')->default(0);
            $table->unsignedTinyInteger('sections')->default(1);
            $table->text('info')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);

            $table->unique(
                ['teacher_cdi', 'sede_id', 'area_id', 'programa_id'],
                'uidx_site_assignment'
            );

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
