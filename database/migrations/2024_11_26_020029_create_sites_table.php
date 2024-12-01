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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('area');
            $table->string('program')->nullable();
            $table->string('uc')->nullable();
            $table->integer('weekHours')->nullable();
            $table->integer('sections')->nullable();
            $table->string('info')->nullable();
            $table->timestamps();
        });

        // Añadir columna site_id a la tabla teachers si no existe
        if (!Schema::hasColumn('teachers', 'site_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->foreignId('site_id')->nullable()->constrained()->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('teachers', 'site_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropForeign(['site_id']);
                $table->dropColumn('site_id');
            });
        }
        Schema::dropIfExists('sites');
    }
};
