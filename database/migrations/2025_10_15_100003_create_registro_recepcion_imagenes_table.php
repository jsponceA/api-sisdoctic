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
        Schema::create('registro_recepcion_imagenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registro_recepcion_id');
            $table->string('foto')->nullable();
            $table->string('descripcion_foto', 255)->nullable();
            $table->string('tipo_foto', 100)->nullable(); // General, Detalle, Embalaje, Estado, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_recepcion_imagenes');
    }
};
