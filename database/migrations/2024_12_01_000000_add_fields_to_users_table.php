<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cdi')->nullable()->unique()->after('email');
            $table->foreignId('site_id')->nullable()->constrained()->after('cdi');
            $table->boolean('is_active')->default(false)->after('site_id');
            $table->boolean('is_approved')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cdi', 'site_id', 'is_active', 'is_approved']);
        });
    }
};
