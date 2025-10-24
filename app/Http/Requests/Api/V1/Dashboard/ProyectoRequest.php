<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProyectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        switch ($this->getMethod()){
            case "POST":
                return [
                    //"codigo_proyecto" => ["nullable", "string", "max:50"],
                    "nombre" => ["required","max:255",new UniqueColumn("proyectos","nombre",null,true)],
                    "fecha_inicio" => ["nullable"],
                    "fecha_fin" => ["nullable"],
                    "descripcion" => ["nullable", "string"],

                    // RESPONSABLES DEL PROYECTO
                    "responsables" => ["nullable", "array"],
                    "responsables.*.responsable_id" => ["required", Rule::exists("responsables","id")],
                    "responsables.*.especialidad_id" => ["nullable", Rule::exists("especialidades","id")],

                    // TIPOS DE DOCUMENTO
                    "tipos_documento" => ["nullable", "array"],
                    "tipos_documento.*.tipo_documento_id" => ["required", Rule::exists("tipos_documento","id")],
                    "tipos_documento.*.dias_plazo" => ["nullable", "integer", "min:0"],
                    "tipos_documento.*.penalidad" => ["nullable", "numeric", "min:0"],

                    // DOCUMENTOS
                    "documentos" => ["nullable", "array"],
                    "documentos.*" => ["nullable", File::types(["pdf", "doc", "docx", "xls", "xlsx"])->max("50mb")],

                    // FOTOGRAFÍAS
                    "fotografias" => ["nullable", "array"],
                    "fotografias.*" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("50mb")],
                ];
            case "PUT":
                return [
                    //"codigo_proyecto" => ["nullable", "string", "max:50"],
                    "nombre" => ["required","max:255",new UniqueColumn("proyectos","nombre",$this->route()->parameter("proyecto"),true)],
                    "fecha_inicio" => ["nullable"],
                    "fecha_fin" => ["nullable"],
                    "descripcion" => ["nullable", "string"],

                    // RESPONSABLES DEL PROYECTO
                    "responsables" => ["nullable", "array"],
                    "responsables.*.responsable_id" => ["required", Rule::exists("responsables","id")],
                    "responsables.*.especialidad_id" => ["nullable", Rule::exists("especialidades","id")],

                    // TIPOS DE DOCUMENTO
                    "tipos_documento" => ["nullable", "array"],
                    "tipos_documento.*.tipo_documento_id" => ["required", Rule::exists("tipos_documento","id")],
                    "tipos_documento.*.dias_plazo" => ["nullable", "integer", "min:0"],
                    "tipos_documento.*.penalidad" => ["nullable", "numeric", "min:0"],

                    // DOCUMENTOS
                    "documentos" => ["nullable", "array"],
                    "documentos.*" => ["nullable", File::types(["pdf", "doc", "docx", "xls", "xlsx"])->max("50mb")],

                    // FOTOGRAFÍAS
                    "fotografias" => ["nullable", "array"],
                    "fotografias.*" => ["nullable", File::image()->extensions(["jpg", "jpeg", "png"])->max("50mb")],
                ];
            default:
                return [];
        }
    }

    public function attributes(): array
    {
        return [
            "codigo_proyecto" => "código de proyecto",
            "nombre" => "nombre del proyecto",
            "fecha_inicio" => "fecha de inicio",
            "fecha_fin" => "fecha de fin",
            "descripcion" => "descripción",
            "responsables" => "responsables",
            "tipos_documento" => "tipos de documento",
            "documentos" => "documentos",
            "fotografias" => "fotografías",
        ];
    }
}
