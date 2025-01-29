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
            $table->foreignId('site_option_id')->constrained();
            $table->foreignId('area_option_id')->constrained('area_options')->nullable(false);
            $table->string('name')->nullable()->unique()->index();
            $table->string('program')->nullable();
            $table->string('uc')->nullable();
            $table->integer('weekHours')->nullable();
            $table->integer('sections')->nullable();
            $table->text('info')->nullable();
            // Control de estado
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
