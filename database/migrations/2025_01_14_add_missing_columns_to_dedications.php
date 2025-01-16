<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dedications', function (Blueprint $table) {
            if (!Schema::hasColumn('dedications', 'director')) {
                $table->string('director')->nullable();
            }
            if (!Schema::hasColumn('dedications', 'studentNumber')) {
                $table->integer('studentNumber')->nullable();
            }
            if (!Schema::hasColumn('dedications', 'studentHours')) {
                $table->integer('studentHours')->nullable();
            }
            if (!Schema::hasColumn('dedications', 'info')) {
                $table->text('info')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('dedications', function (Blueprint $table) {
            $table->dropColumn(['director', 'studentNumber', 'studentHours', 'info']);
        });
    }
};
