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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('verification_code', 64)->nullable()->unique()->after('id');
            $table->foreignId('created_by')->nullable()->after('dedication_id')->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('issued')->after('typeReport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['verification_code', 'created_by', 'status']);
        });
    }
};
