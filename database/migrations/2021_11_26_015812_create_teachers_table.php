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
            $table->string('cdi')->unique()->comment('Cédula de Identidad');
            $table->string('name');
            $table->string('surName');
            $table->enum('genre', ['F', 'M']);
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('birthDate');
            $table->date('datePromotion');
            $table->string('asignaturePromotion')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->constrained('users')->cascadeOnDelete()->unique();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete()->nullable();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('category_id')->constrained('categories')->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('dedication_id')->constrained('dedications')->cascadeOnDelete()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('category_id');
            $table->index('dedication_id');
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
