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

            // Relaciones (DEFINIDAS INICIALMENTE SIN CLAVES FORÁNEAS DENTRO DE SCHEMA::CREATE)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('site_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('dedication_id')->nullable();

            // Control de estado
            $table->boolean('has_site')->default(false)->comment('Indica si ya tiene sede asignada');
            $table->boolean('has_category')->default(false)->comment('Indica si ya tiene categoría asignada');
            $table->boolean('has_dedication')->default(false)->comment('Indica si ya tiene dedicación asignada');
            $table->boolean('is_completed')->default(false)->comment('Indica si todas las relaciones requeridas están completas');
            $table->timestamps();
            $table->softDeletes();
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
