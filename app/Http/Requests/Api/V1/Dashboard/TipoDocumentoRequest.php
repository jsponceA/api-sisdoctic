<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;

class TipoDocumentoRequest extends FormRequest
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
                    "nombre" => ["required","max:255",new UniqueColumn("tipos_documento","nombre",null,true)],
                    "descripcion" => ["nullable","string"],
                ];
            case "PUT":
                return [
                    "nombre" => ["required","max:255",new UniqueColumn("tipos_documento","nombre",$this->route()->parameter("tipos_documento"),true)],
                    "descripcion" => ["nullable","string"],
                ];
            default:
                return [];
        }
    }
}

