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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('preTitle')->nullable()->after('category');
            $table->string('lastTitle')->nullable()->after('preTitle');
            $table->boolean('disable_assistant_rule')->default(false)->after('lastTitle');
            $table->string('current_category')->nullable()->after('disable_assistant_rule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['preTitle', 'lastTitle', 'disable_assistant_rule', 'current_category']);
        });
    }
};
