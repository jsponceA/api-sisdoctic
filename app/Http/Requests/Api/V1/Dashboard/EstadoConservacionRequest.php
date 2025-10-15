<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;

class EstadoConservacionRequest extends FormRequest
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
                    "nombre" => ["required","max:255",new UniqueColumn("estado_conservaciones","nombre",null,true)],
                ];
            case "PUT":
                return [
                    "nombre" => ["required","max:255",new UniqueColumn("estado_conservaciones","nombre",$this->route()->parameter("estados_conservacion"),true)],
                ];
            default:
                return [];
        }
    }
}
