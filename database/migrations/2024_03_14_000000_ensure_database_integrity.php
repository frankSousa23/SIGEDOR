<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Verificar y corregir la tabla users
        if (!Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }

        if (!Schema::hasColumn('users', 'is_approved')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_approved')->default(false);
            });
        }

        // 2. Asegurar que las tablas principales tengan los campos necesarios
        $tables = ['sites', 'categories', 'dedications'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'is_active')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->boolean('is_active')->default(false);
                    $table->integer('teachers_count')->default(0);
                    $table->boolean('is_available')->default(false);
                    $table->timestamp('last_assignment')->nullable();
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        // No es necesario revertir estos cambios ya que son de integridad
    }
};
