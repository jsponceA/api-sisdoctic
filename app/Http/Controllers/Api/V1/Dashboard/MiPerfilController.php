<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\MiPerfilRequest;
use App\Services\Api\V1\Dashboard\MiPerfilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class MiPerfilController extends Controller
{

    private MiPerfilService $miPerfilService;

    public function __construct(MiPerfilService $miPerfilService)
    {
        $this->miPerfilService = $miPerfilService;
    }
    /**
     * Visualizar mi perfil
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["usuario" => $this->miPerfilService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar mi perfil
     * @param int|string $id
     * @param MiPerfilRequest $request
     * @return JsonResponse
     * @requestMediaType multipart/form-data
     */
    public function update(MiPerfilRequest $request, int|string $id)
    {
        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file("foto");
        } else {
            unset($data['foto']);
        }
        //agregar el id del modificador
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $usuario = $this->miPerfilService->modificar($id, $data);
        return response()->json(["message" => "Usuario modificado","usuario" => $usuario], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Listar sesiones abiertas
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function sesionesAbiertas(Request $request,int|string $id)
    {
        return response()->json(["sesiones" => $this->miPerfilService->sesionesAbiertas($id,$request->user()->currentAccessToken()->id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar sesión abierta
     * @param Request $request
     * @param int|string $id
     * @param int|string $tokenId
     * @return JsonResponse
     */
    public function eliminarSesion(Request $request, int|string $id,int | string $tokenId)
    {
        $this->miPerfilService->eliminarSesion($id,$tokenId,$request->user()->currentAccessToken()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Eliminar todas las sesiones abiertas
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function eliminarTodasSesiones(Request $request, int|string $id)
    {
        $this->miPerfilService->eliminarTodasSesiones($id,$request->user()->currentAccessToken()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }
}
