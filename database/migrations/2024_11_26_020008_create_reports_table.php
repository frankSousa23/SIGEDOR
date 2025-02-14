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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade')->nullable();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete()->nullable();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete()->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->nullable();
            $table->foreignId('dedication_id')->constrained('dedications')->onDelete('cascade')->nullable();
            $table->string('report')->index();
            $table->string('memoNumber');
            $table->string('typeReport');
            $table->string('email')->nullable();
            $table->string('info')->nullable();
            $table->timestamps();
            $table->index('teacher_id');
            $table->index('category_id');
            $table->index('dedication_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
