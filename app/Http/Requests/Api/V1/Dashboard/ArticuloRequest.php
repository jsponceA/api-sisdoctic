<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ArticuloRequest extends FormRequest
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
                    "tipo_material_id" => ["nullable","integer","exists:tipo_materiales,id"],
                    "denominacion_id" => ["nullable","integer","exists:denominaciones,id"],
                    "estado_conservacion_id" => ["nullable","integer","exists:estado_conservaciones,id"],
                    "responsable_id" => ["nullable","integer","exists:responsables,id"],
                    "sala_id" => ["nullable","integer","exists:salas,id"],
                    "numero_ficha" => ["nullable","string","max:50"],
                    "codigo_inventario_objeto" => ["nullable","string","max:50"],
                    "numero_registro_nacional" => ["nullable","string","max:50"],
                    "precedencia_sitio" => ["nullable","string","max:255"],
                    "numero_vitrina" => ["nullable","string","max:50"],
                    "descripcion_objeto" => ["nullable","string"],
                    "estado_integridad" => ["nullable","in:1,0,true,false"],
                    "porcentaje_integridad" => ["nullable","integer","min:0","max:100"],
                    "diagnostico_general" => ["nullable","string","max:255"],
                    "proceso_trabajo" => ["nullable","string","max:500"],
                    "indentificacion_fotos" => ["nullable","string","max:150"],
                    "observaciones" => ["nullable","string"],
                    "foto_registro_inicial" => ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "foto_registro_final" => ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "foto_proceso_inicial" =>  ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "foto_proceso_final" =>  ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "fecha_inicio" => ["nullable","date"],
                    "fecha_fin" => ["nullable","date"],
                    "responsables" => ["nullable","array" ],
                ];
            case "PUT":
                return [
                    "tipo_material_id" => ["nullable","integer","exists:tipo_materiales,id"],
                    "denominacion_id" => ["nullable","integer","exists:denominaciones,id"],
                    "estado_conservacion_id" => ["nullable","integer","exists:estado_conservaciones,id"],
                    "responsable_id" => ["nullable","integer","exists:responsables,id"],
                    "sala_id" => ["nullable","integer","exists:salas,id"],
                    "numero_ficha" => ["nullable","string","max:50"],
                    "codigo_inventario_objeto" => ["nullable","string","max:50"],
                    "numero_registro_nacional" => ["nullable","string","max:50"],
                    "precedencia_sitio" => ["nullable","string","max:255"],
                    "numero_vitrina" => ["nullable","string","max:50"],
                    "descripcion_objeto" => ["nullable","string"],
                    "estado_integridad" => ["nullable","in:1,0,true,false"],
                    "porcentaje_integridad" => ["nullable","integer","min:0","max:100"],
                    "diagnostico_general" => ["nullable","string","max:255"],
                    "proceso_trabajo" => ["nullable","string","max:500"],
                    "indentificacion_fotos" => ["nullable","string","max:150"],
                    "observaciones" => ["nullable","string"],
                    "foto_registro_inicial" => ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "foto_registro_final" => ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "foto_proceso_inicial" =>  ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "foto_proceso_final" =>  ["nullable",File::image()->extensions(["jpg","jpeg","png"])->max("20mb")],
                    "fecha_inicio" => ["nullable","date"],
                    "fecha_fin" => ["nullable","date"],
                    "responsables" => ["nullable","array" ],
                ];
            default:
                return [];
        }
    }

    public function attributes(): array
    {
        return [
            "tipo_material_id" => "tipo de material",
            "denominacion_id" => "denominación",
            "estado_conservacion_id" => "estado de conservación",
            "responsable_id" => "responsable",
            "sala_id" => "sala",
            "numero_ficha" => "número de ficha",
            "codigo_inventario_objeto" => "código de inventario del objeto",
            "numero_registro_nacional" => "número de registro nacional",
            "precedencia_sitio" => "precedencia del sitio",
            "numero_vitrina" => "número de vitrina",
            "descripcion_objeto" => "descripción del objeto",
            "estado_integridad" => "estado de integridad",
            "porcentaje_integridad" => "porcentaje de integridad",
            "diagnostico_general" => "diagnóstico general",
            "proceso_trabajo" => "proceso de trabajo",
            "indentificacion_fotos" => "identificación de fotos",
            "observaciones" => "observaciones",
            "foto_registro_inicial" => "foto de registro inicial",
            "foto_registro_final" => "foto de registro final",
            "foto_proceso_inicial" => "foto de proceso inicial",
            "foto_proceso_final" => "foto de proceso final",
            "fecha_inicio" => "fecha de inicio",
            "fecha_fin" => "fecha de fin",
        ];
    }
}
