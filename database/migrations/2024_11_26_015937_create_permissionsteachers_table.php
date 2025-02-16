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
        Schema::create('permissionsteachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_cdi', 15)->index()->comment('CDI del docente');
            $table->foreign('teacher_cdi')->references('cdi')->on('teachers')->onDelete('cascade');
            $table->string('memo_number')->unique()->index();
            $table->enum('type', ['Año Sabático', 'Comisión de Servicio', 'Renovación o Prórroga', 'Incapacidad', 'Por Cuido']);
            $table->boolean('is_paid')->default(false);
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('duration_type', ['semestral', 'anual', 'libre'])->default('semestral');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissionsteachers');
    }
};
