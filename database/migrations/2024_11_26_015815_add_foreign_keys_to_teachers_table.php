<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('site_id')
                  ->references('id')
                  ->on('sites')
                  ->onDelete('set null');
                  
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
                  
            $table->foreign('dedication_id')
                  ->references('id')
                  ->on('dedications')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['site_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['dedication_id']);
        });
    }
};
