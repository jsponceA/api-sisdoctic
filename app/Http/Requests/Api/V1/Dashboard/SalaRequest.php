<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;

class SalaRequest extends FormRequest
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
                    "nombre" => ["required","max:255",new UniqueColumn("salas","nombre",null,true)],
                ];
            case "PUT":
                return [
                    "nombre" => ["required","max:255",new UniqueColumn("salas","nombre",$this->route()->parameter("sala"),true)],
                ];
            default:
                return [];
        }
    }
}
