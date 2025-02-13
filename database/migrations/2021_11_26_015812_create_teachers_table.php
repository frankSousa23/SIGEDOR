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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('cdi')->unique()->comment('Cédula de Identidad')->index();
            $table->string('name');
            $table->string('surName');
            $table->enum('genre', ['F', 'M']);
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('birthDate');
            $table->date('datePromotion');
            $table->string('asignaturePromotion')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->foreignId('programa_id')->nullable()->constrained('programas')->onDelete('set null');
            $table->unsignedBigInteger('site_id')->constrained('sites')->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('category_id')->constrained('categories')->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('dedication_id')->constrained('dedications')->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('report_id')->constrained('reports')->cascadeOnDelete()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('site_id');
            $table->index('category_id');
            $table->index('dedication_id');
            $table->index('report_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
