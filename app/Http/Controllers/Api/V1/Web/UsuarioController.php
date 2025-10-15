<?php

namespace App\Http\Controllers\Api\V1\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            ["usuarios" => []], // Aquí deberías implementar la lógica para obtener los usuarios
            200 // Código de estado HTTP OK
        );
    }
}
