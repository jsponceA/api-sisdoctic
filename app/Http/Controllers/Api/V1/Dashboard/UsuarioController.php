<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\UsuarioExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\UsuarioRequest;
use App\Models\User;
use App\Services\Api\V1\Dashboard\UsuarioService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class UsuarioController extends Controller implements HasMiddleware
{
    private UsuarioService $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("usuarios-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("usuarios-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("usuarios-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("usuarios-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("usuarios-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("usuarios-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Usuarios
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            "search" => ["nullable", "string", "max:255"],
            "per_page" => ["nullable", "integer", "min:1"],
            "take" => ["nullable", "integer", "min:0"],
            "current_page" => ["nullable", "integer", "min:1"],
            "list_all" => ["nullable", "in:1,0,true,false"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);
        return response()->json(["usuarios" => $this->usuarioService->queryListado($request->all())], ResponseHttpCode::HTTP_OK);
    }


    /**
    * Guardar Usuario
     * @param UsuarioRequest $request
     * @return JsonResponse
     * @requestMediaType multipart/form-data
     */
    public function store(UsuarioRequest $request)
    {
        $data = $request->all();

        //agregamos el archivo al array para pasarlo al servicio
        if ($request->hasFile("foto")){
            $data["foto"] = $request->file("foto");
        }else{
            unset($data["foto"]);
        }
        //agregar el id del creador
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->usuarioService->guardar($data);
        return response()->json(["message" => "Usuario creado"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Usuario
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["usuario" => $this->usuarioService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Usuario
     * @param int|string $id
     * @param UsuarioRequest $request
     * @return JsonResponse
     * @requestMediaType multipart/form-data
     */
    public function update(UsuarioRequest $request, int|string $id)
    {
        $data = $request->all();

        //agregamos el archivo al array para pasarlo al servicio
        if ($request->hasFile("foto")){
            $data["foto"] = $request->file("foto");
        }else{
            unset($data["foto"]);
        }
        //agregar el id del modificador
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $usuario = $this->usuarioService->modificar($id,$data);
        return response()->json(["message" => "Usuario modificado","usuario"=>$usuario], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Usuario
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->usuarioService->eliminar($id, auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Habilitar Usuario
     * @param int|string $id
     * @return JsonResponse
     */
    public function habilitar(int|string $id)
    {
        $this->usuarioService->habilitar($id, auth()->user()->id);
        return response()->json(["message" => "Usuario habilitado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * deshabilitar Usuario
     * @param int|string $id
     * @return JsonResponse
     */
    public function deshabilitar(int|string $id)
    {
        $this->usuarioService->deshabilitar($id, auth()->user()->id);
        //borramos el token para que no pueda hacer ninguna solicitud
        User::query()->findOrFail($id)->tokens()->delete();
        return response()->json(["message" => "Usuario deshabilitado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Reporte PDF de Listado de Usuarios
     * @param Request $request
     * @return Response
     */
    public function reportePdf(Request $request)
    {
        $request->validate([
            "search" => ["nullable", "string", "max:255"],
            "per_page" => ["nullable", "integer", "min:1"],
            "take" => ["nullable", "integer", "min:0"],
            "current_page" => ["nullable", "integer", "min:1"],
            "list_all" => ["nullable", "boolean"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);
        $usuarios = $this->usuarioService->queryListado($request->mergeIfMissing(["list_all" => true])->all());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.usuario.listado_pdf",compact("usuarios"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_usuarios.pdf");
    }


    /**
     * Reporte EXCEL de Listado de Usuarios
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function reporteExcel(Request $request)
    {
        $request->validate([
            "search" => ["nullable", "string", "max:255"],
            "per_page" => ["nullable", "integer", "min:1"],
            "take" => ["nullable", "integer", "min:0"],
            "current_page" => ["nullable", "integer", "min:1"],
            "list_all" => ["nullable", "boolean"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);
        return Excel::download(new UsuarioExport($this->usuarioService,$request->mergeIfMissing(["list_all" => true])->all()),"reporte_usuarios.xlsx");
    }
}
