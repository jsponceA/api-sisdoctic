<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            // PROYECTO ASOCIADO
            "proyecto_id" => ["required", Rule::exists("proyectos", "id")],
            // DATOS DE RECEPCIÓN
            "fecha_emision" => ["nullable"],
            "fecha_recepcion" => ["nullable"],
            "fecha_entrega_area" => ["nullable"],
            "tipo_documento_id" => ["nullable",Rule::exists("tipos_documento", "id")],
            "num_doc_recep" => ["nullable", "string", "max:100"],
            "asunto" => ["nullable", "string", "max:500"],
            "tipo_documento_clasificacion_id" => ["nullable", Rule::exists("tipos_documento_clasificacion", "id")],
            "especialidad_id" => ["nullable", Rule::exists("especialidades", "id")],
            // RESPONSABLES DE DESTINO
            "responsables_destino" => ["nullable", "array"],
            "responsables_destino.*" => ["nullable", Rule::exists("responsables","id")],


            // DATOS DE RESPUESTA
            "fecha_respuesta" => ["nullable"],
            "num_docs_resp" => ["nullable", "string", "max:100"],
            "tipo_documento_clasificacion_id_resp" => ["nullable", Rule::exists("tipos_documento_clasificacion", "id")],
            "atencion" => ["nullable", "string", "max:150"],
            "acciones_observaciones" => ["nullable", "string"],


            // ESTADO DE RESPUESTA
            "prioridad" => ["nullable", "in:1,2,3"],
            "situacion" => ["nullable", "string", "in:R,SR"],
            "num_dias_sin_responder" => ["nullable", "integer", "min:0"],
            "fecha_para_responder" => ["nullable"],
            "estado_documento" => ["nullable", "string", "max:100"],

            // DOCUMENTOS ADJUNTOS
            "documentos_adjuntos" => ["nullable", "array"],
            "documentos_adjuntos.*" => ["nullable", File::types(["pdf", "doc", "docx", "xls", "xlsx"])->max("20mb")],

            // DOCUMENTOS DE RESPUESTA
            "documentos_respuesta" => ["nullable", "array"],
            "documentos_respuesta.*" => ["nullable", File::types(["pdf", "doc", "docx", "xls", "xlsx"])->max("20mb")],


        ];
    }

    public function attributes(): array
    {
        return [
            "proyecto_id" => "proyecto",
            "fecha_emision" => "fecha de emisión",
            "fecha_recepcion" => "fecha de recepción",
            "fecha_entrega_area" => "fecha de entrega al área",
            "tipo_documento_id" => "tipo de documento",
            "num_doc_recep" => "número de documento de recepción",
            "asunto" => "asunto",
            "tipo_documento_clasificacion_id" => "tipo de documento de clasificación",
            "especialidad_id" => "especialidad",
            "destino" => "destino",
            "fecha_respuesta" => "fecha de respuesta",
            "num_docs_resp" => "número de documentos de respuesta",
            "atencion" => "atención",
            "acciones_observaciones" => "acciones y observaciones",
            "prioridad" => "prioridad",
            "situacion" => "situación",
            "num_dias_sin_responder" => "número de días sin responder",
            "fecha_para_responder" => "fecha para responder",
            "documentos_adjuntos" => "documentos adjuntos",
            "documentos_respuesta" => "documentos de respuesta",
        ];
    }
}
