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
        Schema::create('registro_recepciones', function (Blueprint $table) {
            $table->id();
            // Auditoría
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('eliminado_por_usuario_id')->nullable();

            // PROYECTO ASOCIADO
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('tipo_documento_id')->nullable();
            $table->unsignedBigInteger('tipo_documento_clasificacion_id')->nullable();
            $table->unsignedBigInteger('especialidad_id')->nullable();

            // DATOS DE RECEPCIÓN
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->date('fecha_entrega_area')->nullable();
            $table->string('num_doc_recep', 100)->nullable();
            $table->string('asunto', 500)->nullable();
            $table->string('destino', 255)->nullable();

            // DATOS DE RESPUESTA
            $table->date('fecha_respuesta')->nullable();
            $table->string('num_docs_resp', 100)->nullable();
            $table->string('atencion', 255)->nullable();
            $table->text('acciones_observaciones')->nullable();

            // ESTADO DE RESPUESTA
            $table->tinyInteger('prioridad')->nullable(); // 1, 2, 3
            $table->enum('situacion', ['R', 'SR'])->default('SR')->nullable(); // R=Respondido, SR=Sin Responder
            $table->integer('num_dias_sin_responder')->nullable();
            $table->date('fecha_para_responder')->nullable();

            $table->string('estado_documento', 100)->nullable();

            $table->datetimes();
            $table->softDeletesDatetime();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_recepciones');
    }
};
