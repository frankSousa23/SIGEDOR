<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_teacher', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                  ->constrained('teachers', 'id', 'fk_site_teacher_t_id')
                  ->onDelete('cascade');

            $table->foreignId('site_id')
                  ->constrained('sites', 'id', 'fk_site_teacher_s_id')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_teacher');
    }
};
