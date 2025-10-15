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
        Schema::create('registro_recepcion_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registro_recepcion_id');
            $table->string('archivo')->nullable();
            $table->string('nombre_documento', 255)->nullable();
            $table->string('tipo_documento', 100)->nullable(); // Oficio, Carta, Acta, Factura, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_recepcion_documentos');
    }
};

