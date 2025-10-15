<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ConfiguracionEmpresaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "nombre_comercial" => ["nullable","max:255"],
            "nombre_corto" => ["nullable","max:255"],
            "ruc" => ["nullable","max:50"],
            "telefonos" => ["nullable","max:50"],
            "correo" => ["nullable","max:150"],
            "direccion" =>["nullable","max:255"],
            "logo" => ["nullable",File::image(true)->extensions(["jpg","png","jpeg","svg"])->max("20mb")],
            "favicon" => ["nullable",File::default()->extensions(["jpg","png","jpeg","svg","ico"])->max("20mb")],
        ];
    }

}
