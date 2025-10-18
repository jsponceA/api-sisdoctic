<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;

class TipoDocumentoClasificacionRequest extends FormRequest
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
                    "nombre" => ["required","max:255",new UniqueColumn("tipos_documento_clasificacion","nombre",null,true)],
                    "descripcion" => ["nullable","string"],
                ];
            case "PUT":
                return [
                    "nombre" => ["required","max:255",new UniqueColumn("tipos_documento_clasificacion","nombre",$this->route()->parameter("tipos_documento_clasificacion"),true)],
                    "descripcion" => ["nullable","string"],
                ];
            default:
                return [];
        }
    }
}

