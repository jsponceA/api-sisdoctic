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
        Schema::create('configuracion_empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->string('nombre_comercial')->nullable();
            $table->string('nombre_corto')->nullable();
            $table->string('ruc',50)->nullable();
            $table->string('telefonos',50)->nullable();
            $table->string('correo',150)->nullable();
            $table->string('direccion')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_empresas');
    }
};
