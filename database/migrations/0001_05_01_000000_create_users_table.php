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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->foreignId('sede_id')->constrained('sedes')->onDelete('cascade')->nullable(false);
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade')->nullable(false);
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
