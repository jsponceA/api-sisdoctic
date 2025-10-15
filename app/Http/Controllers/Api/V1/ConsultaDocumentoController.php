<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\ConsultaDocumentoService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ConsultaDocumentoController extends Controller implements HasMiddleware
{
    private ConsultaDocumentoService $consultaDocumentoService;
    public function __construct(ConsultaDocumentoService $consultaDocumentoService)
    {
        $this->consultaDocumentoService = $consultaDocumentoService;
    }

    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum")
        ];
    }

    /**
     * Buscar DNI
     *
     * @param string $dni
     * @return JsonResponse
     */
    public function buscarDni(string $dni)
    {
        $response = $this->consultaDocumentoService->buscarDni($dni);
        if ($response["status"] != 200) {
            return response()->json($response, Response::HTTP_NOT_FOUND);
        }
            return response()->json(["dni" => $response], Response::HTTP_OK);
    }

    /**
     * Buscar RUC
     *
     * @param string $ruc
     * @return JsonResponse
     */
    public function buscarRuc(string $ruc)
    {
            return response()->json(["ruc" => $this->consultaDocumentoService->buscarRuc($ruc)], Response::HTTP_OK);
    }


}
