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
        Schema::create('registro_recepcion_documentos_respuesta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registro_recepcion_id');
            $table->string('archivo')->nullable();
            $table->string('nombre_original', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_recepcion_documentos_respuesta');
    }
};

