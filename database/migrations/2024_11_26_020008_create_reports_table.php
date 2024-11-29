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
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('dedication_id')->nullable();
            $table->foreignId('permission_id')->nullable();
            $table->foreignId('site_id')->nullable();
            $table->string('report');
            $table->string('memoNumber');
            $table->string('typeReport');
            $table->string('email')->nullable();
            $table->string('info')->nullable();
            $table->timestamps();
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
