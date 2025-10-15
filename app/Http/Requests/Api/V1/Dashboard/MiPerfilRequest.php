<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class MiPerfilRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "username" => ["required","max:100", new UniqueColumn("users","username",$this->route()->parameter("mi_perfil"),true)],
            "password" => ["nullable","max:100"],
            "nombres" => ["required","max:100"],
            "apellidos"  => ["required","max:100"],
            "correo" => ["nullable","max:150","email"],
            "foto" => ["nullable","image",File::image()->extensions(["jpg","png","jpeg"])->max("10mb")],
        ];
    }

    public function attributes()
    {
        return [
            "username" => "usuario",
            "password" => "contraseña"
        ];
    }
}
