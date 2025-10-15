<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionEmpresaController extends Controller
{
    /**
     * Visualizar la informacion de la empresa (logo, nombre, etc)
     * @return JsonResponse
     */
    public function mostrarInformacionGeneral()
    {
        $configuracionEmpresa = ConfiguracionEmpresa::query()
        ->select("nombre_corto","nombre_comercial","logo","favicon")
        ->latest()->first();

        return response()->json(['configuracion_empresa' => $configuracionEmpresa], Response::HTTP_OK);
    }
}
