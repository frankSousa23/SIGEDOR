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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cdi')->unique();
            $table->string('name');
            $table->string('surName');
            $table->enum('genre', ['M', 'F']);
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('birthDate');
            $table->date('datePromotion');
            $table->string('asignaturePromotion');
            $table->timestamps();
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
