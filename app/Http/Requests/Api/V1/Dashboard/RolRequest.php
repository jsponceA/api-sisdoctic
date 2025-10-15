<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;

class RolRequest extends FormRequest
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
                    "name" => ["required","max:255",new UniqueColumn("roles","name",null,false)],
                    "permissions" => ["required","array","min:1"]
                ];
            case "PUT":
                return [
                    "name" => ["required","max:255",new UniqueColumn("roles","name",$this->route()->parameter("role"),false)],
                    "permissions" => ["required","array","min:1"]
                ];
            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            "name" => "nombre",
            "permissions" => "permisos"
        ];
    }
}
