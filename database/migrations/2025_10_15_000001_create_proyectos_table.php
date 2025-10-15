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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('eliminado_por_usuario_id')->nullable();
            $table->unsignedBigInteger("categoria_id")->nullable();
            $table->unsignedBigInteger("responsable_id")->nullable();

            // I. DATOS DE IDENTIFICACIÓN
            $table->string('codigo_proyecto', 50)->nullable();
            $table->string('nombre_proyecto', 255)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('ubicacion', 255)->nullable();
            $table->string('estado', 50)->nullable(); // En planificación, En ejecución, Completado, Suspendido
            $table->text('descripcion')->nullable();

            // II. DATOS TÉCNICOS
            $table->decimal('presupuesto', 12, 2)->nullable();
            $table->string('fuente_financiamiento', 255)->nullable();
            $table->text('objetivos')->nullable();
            $table->text('alcance')->nullable();
            $table->integer('numero_beneficiarios')->nullable();

            // III. EQUIPO Y RECURSOS
            $table->text('equipo_trabajo')->nullable();
            $table->text('recursos_materiales')->nullable();
            $table->text('recursos_tecnologicos')->nullable();

            // IV. SEGUIMIENTO
            $table->integer('porcentaje_avance')->default(0);
            $table->text('resultados_obtenidos')->nullable();
            $table->text('dificultades_encontradas')->nullable();
            $table->text('lecciones_aprendidas')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('recomendaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};

