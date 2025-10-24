<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class IntervencionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $commonRules = [
            // Relaciones
            "categoria_id" => ["nullable", "exists:categorias,id"],
            "denominacion_id" => ["nullable", "exists:denominaciones,id"],
            "director_encargado_id" => ["nullable",  "exists:responsables,id"],
            "conservador_responsable_id" => ["nullable", "exists:responsables,id"],

            // I. DATOS DE IDENTIFICACIÓN
            "numero_ficha" => ["nullable", "string", "max:50"],
            "fecha_recepcion" => ["nullable", "date"],
            "fecha_entrega" => ["nullable", "date"],
            "numero_registro_nacional" => ["nullable", "string", "max:100"],
            "numero_inventario" => ["nullable", "string", "max:100"],
            "codigo_museo" => ["nullable", "string", "max:100"],
            "otros_codigos" => ["nullable", "string", "max:100"],
            "cultura_estilo_autor" => ["nullable", "string", "max:150"],
            "periodo_epoca" => ["nullable", "string", "max:150"],
            "procedencia" => ["nullable", "string"],
            "descripcion" => ["nullable", "string", "max:500"],

            // II. DATOS TÉCNICOS
            "material" => ["nullable", "string", "max:200"],
            "tecnicas" => ["nullable", "string", "max:200"],
            "alto" => ["nullable", "string", "max:100"],
            "largo" => ["nullable", "string", "max:100"],
            "ancho" => ["nullable", "string", "max:100"],
            "profundidad" => ["nullable", "string", "max:100"],
            "diametro_maximo" => ["nullable", "string", "max:100"],
            "diametro_minimo" => ["nullable", "string", "max:100"],
            "peso_inicial" => ["nullable", "string", "max:100"],
            "peso_final" => ["nullable", "string", "max:100"],

            // III. ESTADO DE CONSERVACIÓN
            "integridad" => ["nullable", "array"],
            "numero_fragmentos" => ["nullable", "string", "max:100"],
            "tipo_dano" => ["nullable", "array"],
            "otros_tipo_dano" => ["nullable", "string", "max:200"],
            "agentes_deterioro" => ["nullable", "array"],
            "condiciones_exposicion_almacenaje" => ["nullable", "string", "max:500"],
            "intervenciones_anteriores" => ["nullable", "string", "max:500"],
            "analisis_realizados" => ["nullable", "string", "max:500"],
            "diagnostico" => ["nullable", "string", "max:500"],

            // IV. PROCESO DE INTERVENCIÓN
            "resultado_tiempo_empleado" => ["nullable", "string", "max:500"],
            "embalaje_soporte" => ["nullable", "string"],
            "observaciones" => ["nullable", "string", "max:500"],
            "recomendaciones" => ["nullable", "string", "max:500"],

            // Procesos de intervención (tabla relacionada)
            "procesos" => ["nullable", "array"],
            "procesos.*.area_material_intervenido" => ["nullable", "string"],
            "procesos.*.intervencion_tratamiento" => ["nullable", "string"],
            "procesos.*.insumo_producto_herramiental" => ["nullable", "string"],
            "procesos.*.procedimiento" => ["nullable", "string", "max:500"],

            // Vistas generales (tabla relacionada)
            "vista_generales" => ["nullable", "array"],
            "vista_generales.*" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("50mb")],

            // Vistas detalles (tabla relacionada)
            "vista_detalles" => ["nullable", "array"],
            "vista_detalles.*" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("50mb")],

            // Vistas intervenciones (tabla relacionada)
            "vista_intervenciones" => ["nullable", "array"],
            "vista_intervenciones.*" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("50mb")],
        ];

        return $commonRules;
    }

    public function attributes(): array
    {
        return [
            "categoria_id" => "categoría",
            "denominacion_id" => "denominación",
            "director_encargado_id" => "director encargado",
            "conservador_responsable_id" => "conservador responsable",
            "numero_ficha" => "número de ficha",
            "fecha_recepcion" => "fecha de recepción",
            "fecha_entrega" => "fecha de entrega",
            "numero_registro_nacional" => "número de registro nacional",
            "numero_inventario" => "número de inventario",
            "codigo_museo" => "código de museo",
            "otros_codigos" => "otros códigos",
            "cultura_estilo_autor" => "cultura/estilo/autor",
            "periodo_epoca" => "período/época",
            "procedencia" => "procedencia",
            "descripcion" => "descripción",
            "material" => "material",
            "tecnicas" => "técnicas",
            "alto" => "alto",
            "largo" => "largo",
            "ancho" => "ancho",
            "profundidad" => "profundidad",
            "diametro_maximo" => "diámetro máximo",
            "diametro_minimo" => "diámetro mínimo",
            "peso_inicial" => "peso inicial",
            "peso_final" => "peso final",
            "integridad" => "integridad",
            "numero_fragmentos" => "número de fragmentos",
            "tipo_dano" => "tipo de daño",
            "otros_tipo_dano" => "otros tipos de daño",
            "agentes_deterioro" => "agentes de deterioro",
            "condiciones_exposicion_almacenaje" => "condiciones de exposición/almacenaje",
            "intervenciones_anteriores" => "intervenciones anteriores",
            "analisis_realizados" => "análisis realizados",
            "diagnostico" => "diagnóstico",
            "resultado_tiempo_empleado" => "resultado/tiempo empleado",
            "embalaje_soporte" => "embalaje/soporte",
            "observaciones" => "observaciones",
            "recomendaciones" => "recomendaciones",
            "procesos" => "procesos de intervención",
            "vista_generales" => "vistas generales",
            "vista_detalles" => "vistas de detalle",
            "vista_intervenciones" => "vistas de intervención",
        ];
    }
}
