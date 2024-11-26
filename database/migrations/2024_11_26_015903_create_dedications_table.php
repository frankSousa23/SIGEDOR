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
        Schema::create('dedications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id');
            $table->string('dedication');
            $table->string('tcv');
            $table->string('mt');
            $table->string('tc');
            $table->string('exclusive');
            $table->string('info')->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dedications');
    }
};
