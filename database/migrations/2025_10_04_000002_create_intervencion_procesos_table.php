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
        Schema::create('intervencion_procesos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intervencion_id');
            $table->string('area_material_intervenido')->nullable();
            $table->string('intervencion_tratamiento')->nullable();
            $table->string('insumo_producto_herramiental')->nullable();
            $table->string('procedimiento',500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervencion_procesos');
    }
};

