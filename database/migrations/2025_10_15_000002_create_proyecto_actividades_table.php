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
        Schema::create('proyecto_actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id');
            $table->string('nombre_actividad')->nullable();
            $table->text('descripcion_actividad')->nullable();
            $table->date('fecha_programada')->nullable();
            $table->string('responsable_actividad')->nullable();
            $table->string('estado_actividad', 50)->nullable(); // Pendiente, En proceso, Completada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_actividades');
    }
};

