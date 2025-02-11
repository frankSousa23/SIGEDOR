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
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade')->nullable();
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade')->nullable();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade')->nullable();
            $table->foreignId('programa_id')->constrained('programas')->onDelete('cascade')->nullable();
            $table->string('uc')->nullable();
            $table->integer('weekHours')->nullable();
            $table->integer('sections')->nullable();
            $table->text('info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('teachers_count')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamp('last_assignment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
