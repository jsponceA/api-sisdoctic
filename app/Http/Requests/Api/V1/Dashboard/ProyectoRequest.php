<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ProyectoRequest extends FormRequest
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
            "responsable_id" => ["nullable", "exists:responsables,id"],

            // I. DATOS DE IDENTIFICACIÓN
            "codigo_proyecto" => ["nullable", "string", "max:50"],
            "nombre_proyecto" => ["nullable", "string", "max:255"],
            "fecha_inicio" => ["nullable", "date"],
            "fecha_fin" => ["nullable", "date"],
            "ubicacion" => ["nullable", "string", "max:255"],
            "estado" => ["nullable", "string", "max:50"],
            "descripcion" => ["nullable", "string"],

            // II. DATOS TÉCNICOS
            "presupuesto" => ["nullable", "numeric", "min:0"],
            "fuente_financiamiento" => ["nullable", "string", "max:255"],
            "objetivos" => ["nullable", "string"],
            "alcance" => ["nullable", "string"],
            "numero_beneficiarios" => ["nullable", "integer", "min:0"],

            // III. EQUIPO Y RECURSOS
            "equipo_trabajo" => ["nullable", "string"],
            "recursos_materiales" => ["nullable", "string"],
            "recursos_tecnologicos" => ["nullable", "string"],

            // IV. SEGUIMIENTO
            "porcentaje_avance" => ["nullable", "integer", "min:0", "max:100"],
            "resultados_obtenidos" => ["nullable", "string"],
            "dificultades_encontradas" => ["nullable", "string"],
            "lecciones_aprendidas" => ["nullable", "string"],
            "observaciones" => ["nullable", "string"],
            "recomendaciones" => ["nullable", "string"],

            // Actividades del proyecto (tabla relacionada)
            "actividades" => ["nullable", "array"],
            "actividades.*.nombre_actividad" => ["nullable", "string"],
            "actividades.*.descripcion_actividad" => ["nullable", "string"],
            "actividades.*.fecha_programada" => ["nullable", "date"],
            "actividades.*.responsable_actividad" => ["nullable", "string"],
            "actividades.*.estado_actividad" => ["nullable", "string", "max:50"],

            // Documentos (tabla relacionada)
            "documentos" => ["nullable", "array"],
            "documentos.*" => ["nullable", File::types(["pdf", "doc", "docx", "xls", "xlsx"])->max("20mb")],

            // Imágenes (tabla relacionada)
            "imagenes" => ["nullable", "array"],
            "imagenes.*" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("20mb")],
        ];

        return $commonRules;
    }

    public function attributes(): array
    {
        return [
            "categoria_id" => "categoría",
            "responsable_id" => "responsable",
            "codigo_proyecto" => "código de proyecto",
            "nombre_proyecto" => "nombre del proyecto",
            "fecha_inicio" => "fecha de inicio",
            "fecha_fin" => "fecha de fin",
            "ubicacion" => "ubicación",
            "estado" => "estado",
            "descripcion" => "descripción",
            "presupuesto" => "presupuesto",
            "fuente_financiamiento" => "fuente de financiamiento",
            "objetivos" => "objetivos",
            "alcance" => "alcance",
            "numero_beneficiarios" => "número de beneficiarios",
            "equipo_trabajo" => "equipo de trabajo",
            "recursos_materiales" => "recursos materiales",
            "recursos_tecnologicos" => "recursos tecnológicos",
            "porcentaje_avance" => "porcentaje de avance",
            "resultados_obtenidos" => "resultados obtenidos",
            "dificultades_encontradas" => "dificultades encontradas",
            "lecciones_aprendidas" => "lecciones aprendidas",
            "observaciones" => "observaciones",
            "recomendaciones" => "recomendaciones",
            "actividades" => "actividades",
            "documentos" => "documentos",
            "imagenes" => "imágenes",
        ];
    }
}

