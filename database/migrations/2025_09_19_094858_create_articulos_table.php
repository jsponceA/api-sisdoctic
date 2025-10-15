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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_material_id')->nullable();
            $table->unsignedBigInteger('denominacion_id')->nullable();
            $table->unsignedBigInteger('estado_conservacion_id')->nullable();
            $table->unsignedBigInteger('sala_id')->nullable();
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('eliminado_por_usuario_id')->nullable();
            $table->string('numero_ficha',50)->nullable();
            $table->string('codigo_inventario_objeto',50)->nullable();
            $table->string('numero_registro_nacional',50)->nullable();
            $table->string('precedencia_sitio')->nullable();
            $table->string('numero_vitrina',50)->nullable();
            $table->string('descripcion_objeto')->nullable();
            $table->boolean('estado_integridad')->default(false);
            $table->unsignedInteger('porcentaje_integridad')->nullable();
            $table->string('diagnostico_general')->nullable();
            $table->string('proceso_trabajo',500)->nullable();
            $table->string('indentificacion_fotos',150)->nullable();
            $table->string('observaciones')->nullable();
            $table->string('foto_registro_inicial')->nullable();
            $table->string('foto_registro_final')->nullable();
            $table->string('foto_proceso_inicial')->nullable();
            $table->string('foto_proceso_final')->nullable();
            $table->date("fecha_inicio")->nullable();
            $table->date("fecha_fin")->nullable();
            $table->datetimes();
            $table->softDeletesDatetime();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
