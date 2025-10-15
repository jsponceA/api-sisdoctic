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
            // Relaciones
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('eliminado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('tipo_material_id')->nullable();

            // I. DATOS DE IDENTIFICACIÓN
            $table->string('numero_recepcion', 50)->unique()->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->string('tipo_recepcion', 50)->nullable(); // Donación, Compra, Préstamo, Traslado, Otros
            $table->string('procedencia', 255)->nullable();
            $table->string('remitente', 255)->nullable();
            $table->string('documento_referencia', 100)->nullable(); // N° de oficio, carta, etc.

            // II. DESCRIPCIÓN DEL MATERIAL
            $table->text('descripcion_material')->nullable();
            $table->integer('cantidad')->default(1);
            $table->string('unidad_medida', 50)->nullable(); // Unidad, Caja, Paquete, etc.
            $table->decimal('peso_kg', 10, 2)->nullable();
            $table->text('dimensiones')->nullable(); // Alto x Ancho x Largo
            $table->string('estado_conservacion_ingreso', 100)->nullable(); // Bueno, Regular, Malo, Crítico

            // III. CONDICIONES DE RECEPCIÓN
            $table->string('embalaje', 100)->nullable(); // Original, Reembalado, Sin embalaje
            $table->boolean('documentacion_completa')->default(false);
            $table->text('documentos_adjuntos')->nullable(); // Lista de documentos que acompañan
            $table->boolean('registro_fotografico')->default(false);
            $table->text('observaciones_ingreso')->nullable();

            // IV. UBICACIÓN Y ALMACENAMIENTO
            $table->string('ubicacion_temporal', 255)->nullable();
            $table->string('area_almacenamiento', 100)->nullable();
            $table->string('condiciones_ambientales', 255)->nullable(); // Temperatura, humedad, etc.
            $table->boolean('requiere_tratamiento_urgente')->default(false);
            $table->text('tratamiento_requerido')->nullable();

            // V. VALORACIÓN Y CLASIFICACIÓN
            $table->decimal('valor_estimado', 12, 2)->nullable();
            $table->string('clasificacion', 100)->nullable(); // Arqueológico, Histórico, Artístico, etc.
            $table->string('periodo_cronologico', 100)->nullable();
            $table->string('cultura', 100)->nullable();
            $table->boolean('patrimonio_cultural')->default(false);

            // VI. SEGUIMIENTO
            $table->string('estado_proceso', 50)->default('Pendiente'); // Pendiente, En revisión, Catalogado, Almacenado
            $table->text('acciones_realizadas')->nullable();
            $table->date('fecha_revision')->nullable();
            $table->string('revisado_por', 255)->nullable();
            $table->text('observaciones_finales')->nullable();
            $table->text('recomendaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices para mejorar el rendimiento
            $table->index('proyecto_id');
            $table->index('fecha_recepcion');
            $table->index('estado_proceso');
            $table->index('tipo_recepcion');
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

