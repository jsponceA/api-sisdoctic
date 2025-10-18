<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;

class ResponsableRequest extends FormRequest
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
                    "nombres" => ["required","string","max:150"],
                    "apellidos" => ["required","string","max:150"],
                    "correo" => ["nullable","email","max:150"],
                    "telefono" => ["nullable","max:50"],
                    "tipo_documento" => ["nullable","string","max:50"],
                    "numero_documento" => ["nullable","string","max:50",new UniqueColumn("responsables","numero_documento",null,true)],
                ];
            case "PUT":
                return [
                    "nombres" => ["required","string","max:150"],
                    "apellidos" => ["required","string","max:150"],
                    "correo" => ["nullable","email","max:150"],
                    "telefono" => ["nullable","max:50"],
                    "tipo_documento" => ["nullable","string","max:50"],
                    "numero_documento" => ["nullable","string","max:50",new UniqueColumn("responsables","numero_documento",$this->route()->parameter("responsable"),true)],
                ];
            default:
                return [];
        }
    }

    public function attributes(): array
    {
        return [
            "nombres" => "nombres",
            "apellidos" => "apellidos",
            "correo" => "correo electrónico",
            "tipo_documento" => "tipo de documento",
            "numero_documento" => "número de documento",
        ];
    }
}
