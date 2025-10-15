<?php

namespace App\Http\Requests\Api\V1\Dashboard;

use App\Rules\UniqueColumn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->getMethod()){
            case "POST":
                return [
                    "rol" => ["required",Rule::exists("roles","name")],
                    "username" => ["required","max:100", new UniqueColumn("users","username",null,true)],
                    "password" => ["required","max:100"],
                    "nombres" => ["required","max:150"],
                    "apellidos"  => ["required","max:150"],
                    "correo" => ["nullable","max:150","email"],
                    "foto" => ["nullable",File::image(true)->extensions(["jpg","png","jpeg","svg"])->max("10mb")],
                    "estado" => ["nullable","in:1,0,true,false"]
                ];
            case "PUT":
                return [
                    "rol" => ["required",Rule::exists("roles","name")],
                    "username" => ["required","max:100",  new UniqueColumn("users","username",$this->route()->parameter("usuario"),true)],
                    "password" => ["nullable","max:100"],
                    "nombres" => ["required","max:150"],
                    "apellidos"  => ["required","max:150"],
                    "correo" => ["nullable","max:150","email"],
                    "foto" => ["nullable",File::image(true)->extensions(["jpg","png","jpeg","svg"])->max("10mb")],
                    "estado" => ["nullable","in:1,0,true,false"]
                ];
            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            "username" => "usuario",
            "password" => "contraseña"
        ];
    }
}
