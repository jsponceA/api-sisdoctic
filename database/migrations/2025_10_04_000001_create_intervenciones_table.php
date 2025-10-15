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
        Schema::create('intervenciones', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('eliminado_por_usuario_id')->nullable();
            $table->unsignedBigInteger("categoria_id")->nullable();
            $table->unsignedBigInteger("denominacion_id")->nullable();
            $table->unsignedBigInteger("director_encargado_id")->nullable(); //responsable de la intervención
            $table->unsignedBigInteger("conservador_responsable_id")->nullable(); //responsable de la intervención

            // I. DATOS DE IDENTIFICACIÓN
            $table->string('numero_ficha', 50)->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->string('numero_registro_nacional', 100)->nullable();
            $table->string('numero_inventario', 100)->nullable();
            $table->string('codigo_museo', 100)->nullable();
            $table->string('otros_codigos', 100)->nullable();
            $table->string('cultura_estilo_autor', 150)->nullable();
            $table->string('periodo_epoca', 150)->nullable();
            $table->string('procedencia')->nullable();
            $table->string('descripcion',500)->nullable();


            // II. DATOS TÉCNICOS
            $table->string('material', 200)->nullable();
            $table->string('tecnicas', 200)->nullable();
            $table->string('alto', 100)->nullable();
            $table->string('largo', 100)->nullable();
            $table->string('ancho', 100)->nullable();
            $table->string('profundidad', 100)->nullable();
            $table->string('diametro_maximo', 100)->nullable();
            $table->string('diametro_minimo', 100)->nullable();
            $table->string('peso_inicial', 100)->nullable();
            $table->string('peso_final', 100)->nullable();

            // III. ESTADO DE CONSERVACIÓN
            $table->json('integridad')->nullable();
            $table->string('numero_fragmentos',100)->nullable();

            // Tipo de daño (JSON para almacenar checkboxes múltiples)
            $table->json('tipo_dano')->nullable();
            $table->string('otros_tipo_dano', 200)->nullable();

            // Agentes de deterioro
            $table->json('agentes_deterioro')->nullable();

            $table->string('condiciones_exposicion_almacenaje',500)->nullable();
            $table->string('intervenciones_anteriores',500)->nullable();
            $table->string('analisis_realizados',500)->nullable();
            $table->string('diagnostico',500)->nullable();

            // IV. PROCESO DE INTERVENCIÓN (Array de procesos - se manejará con tabla relacionada)
            $table->string('resultado_tiempo_empleado', 500)->nullable();
            $table->string('embalaje_soporte')->nullable();

            $table->string('observaciones',500)->nullable();
            $table->string('recomendaciones',500)->nullable();

            $table->datetimes();
            $table->softDeletesDatetime();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervenciones');
    }
};

