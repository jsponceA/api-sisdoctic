<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\ConfiguracionEmpresaRequest;
use App\Services\Api\V1\Dashboard\ConfiguracionEmpresaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class ConfiguracionEmpresaController extends Controller implements HasMiddleware
{
    private ConfiguracionEmpresaService $configuracionEmpresaService;

    public function __construct(ConfiguracionEmpresaService $configuracionEmpresaService)
    {
        $this->configuracionEmpresaService = $configuracionEmpresaService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("empresa-modificar"),only: ["update"])
        ];
    }

    /**
     * Visualizar mi configuracion de empresa
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["configuracion_empresa" => $this->configuracionEmpresaService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar mi configuracion de empresa
     * @param int|string $id
     * @param ConfiguracionEmpresaRequest $request
     * @return JsonResponse
     * @requestMediaType multipart/form-data
     */
    public function update(ConfiguracionEmpresaRequest $request, int|string $id)
    {
        $data = $request->all();

        if ($request->hasFile("foto")) {
            $data['foto'] = $request->file("foto");
        } else {
            unset($data['foto']);
        }
        if ($request->hasFile("favicon")) {
            $data['favicon'] = $request->file("favicon");
        } else {
            unset($data['favicon']);
        }

        //agregar el id del modificador
        $data["modificado_por_usuario_id"] = auth()->user()->id;

        $configuracionEmpresa = $this->configuracionEmpresaService->modificar($id, $data);
        return response()->json(["message" => "Configuración modificada","configuracion_empresa" => $configuracionEmpresa], ResponseHttpCode::HTTP_OK);
    }

}
