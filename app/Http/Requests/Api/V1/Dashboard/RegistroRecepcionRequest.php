<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class RegistroRecepcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Relaciones
            "proyecto_id" => ["nullable", "exists:proyectos,id"],
            "responsable_id" => ["nullable", "exists:responsables,id"],
            "tipo_material_id" => ["nullable", "exists:tipo_materiales,id"],

            // I. DATOS DE IDENTIFICACIÓN
            "numero_recepcion" => ["nullable", "string", "max:50"],
            "fecha_recepcion" => ["required", "date"],
            "tipo_recepcion" => ["required", "string", "max:50"],
            "procedencia" => ["nullable", "string", "max:255"],
            "remitente" => ["required", "string", "max:255"],
            "documento_referencia" => ["nullable", "string", "max:100"],

            // II. DESCRIPCIÓN DEL MATERIAL
            "descripcion_material" => ["required", "string"],
            "cantidad" => ["required", "integer", "min:1"],
            "unidad_medida" => ["nullable", "string", "max:50"],
            "peso_kg" => ["nullable", "numeric", "min:0"],
            "dimensiones" => ["nullable", "string"],
            "estado_conservacion_ingreso" => ["nullable", "string", "max:100"],

            // III. CONDICIONES DE RECEPCIÓN
            "embalaje" => ["nullable", "string", "max:100"],
            "documentacion_completa" => ["nullable", "boolean"],
            "documentos_adjuntos" => ["nullable", "string"],
            "registro_fotografico" => ["nullable", "boolean"],
            "observaciones_ingreso" => ["nullable", "string"],

            // IV. UBICACIÓN Y ALMACENAMIENTO
            "ubicacion_temporal" => ["nullable", "string", "max:255"],
            "area_almacenamiento" => ["nullable", "string", "max:100"],
            "condiciones_ambientales" => ["nullable", "string", "max:255"],
            "requiere_tratamiento_urgente" => ["nullable", "boolean"],
            "tratamiento_requerido" => ["nullable", "string"],

            // V. VALORACIÓN Y CLASIFICACIÓN
            "valor_estimado" => ["nullable", "numeric", "min:0"],
            "clasificacion" => ["nullable", "string", "max:100"],
            "periodo_cronologico" => ["nullable", "string", "max:100"],
            "cultura" => ["nullable", "string", "max:100"],
            "patrimonio_cultural" => ["nullable", "boolean"],

            // VI. SEGUIMIENTO
            "estado_proceso" => ["nullable", "string", "max:50"],
            "acciones_realizadas" => ["nullable", "string"],
            "fecha_revision" => ["nullable", "date"],
            "revisado_por" => ["nullable", "string", "max:255"],
            "observaciones_finales" => ["nullable", "string"],
            "recomendaciones" => ["nullable", "string"],

            // Documentos (tabla relacionada)
            "documentos" => ["nullable", "array"],
            "documentos.*.archivo" => ["nullable", File::types(["pdf", "doc", "docx", "xls", "xlsx"])->max("20mb")],
            "documentos.*.nombre_documento" => ["nullable", "string", "max:255"],
            "documentos.*.tipo_documento" => ["nullable", "string", "max:100"],

            // Imágenes (tabla relacionada)
            "imagenes" => ["nullable", "array"],
            "imagenes.*.foto" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("20mb")],
            "imagenes.*.descripcion_foto" => ["nullable", "string", "max:255"],
            "imagenes.*.tipo_foto" => ["nullable", "string", "max:100"],
        ];
    }

    public function attributes(): array
    {
        return [
            "proyecto_id" => "proyecto",
            "responsable_id" => "responsable",
            "tipo_material_id" => "tipo de material",
            "numero_recepcion" => "número de recepción",
            "fecha_recepcion" => "fecha de recepción",
            "tipo_recepcion" => "tipo de recepción",
            "procedencia" => "procedencia",
            "remitente" => "remitente",
            "documento_referencia" => "documento de referencia",
            "descripcion_material" => "descripción del material",
            "cantidad" => "cantidad",
            "unidad_medida" => "unidad de medida",
            "peso_kg" => "peso en kg",
            "dimensiones" => "dimensiones",
            "estado_conservacion_ingreso" => "estado de conservación al ingreso",
            "embalaje" => "embalaje",
            "documentacion_completa" => "documentación completa",
            "documentos_adjuntos" => "documentos adjuntos",
            "registro_fotografico" => "registro fotográfico",
            "observaciones_ingreso" => "observaciones de ingreso",
            "ubicacion_temporal" => "ubicación temporal",
            "area_almacenamiento" => "área de almacenamiento",
            "condiciones_ambientales" => "condiciones ambientales",
            "requiere_tratamiento_urgente" => "requiere tratamiento urgente",
            "tratamiento_requerido" => "tratamiento requerido",
            "valor_estimado" => "valor estimado",
            "clasificacion" => "clasificación",
            "periodo_cronologico" => "período cronológico",
            "cultura" => "cultura",
            "patrimonio_cultural" => "patrimonio cultural",
            "estado_proceso" => "estado del proceso",
            "acciones_realizadas" => "acciones realizadas",
            "fecha_revision" => "fecha de revisión",
            "revisado_por" => "revisado por",
            "observaciones_finales" => "observaciones finales",
            "recomendaciones" => "recomendaciones",
        ];
    }
}

