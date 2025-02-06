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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_temporary')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('site_option_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('area_option_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null'); // Added role_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
