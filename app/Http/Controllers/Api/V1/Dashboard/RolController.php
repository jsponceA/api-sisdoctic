<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\RolExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\RolRequest;
use App\Services\Api\V1\Dashboard\RolService;
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

class RolController extends Controller implements  HasMiddleware
{
    protected RolService $rolService;
    public function __construct(RolService $rolService)
    {
        $this->rolService = $rolService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("roles-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("roles-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("roles-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("roles-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("roles-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("roles-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Roles
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

        return response()->json(["roles" => $this->rolService->queryListado($request->all())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Rol
     * @param RolRequest $request
     * @return JsonResponse
     */
    public function store(RolRequest $request)
    {
        $this->rolService->guardar($request->all());
        return response()->json(["message" => "Rol creado"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Rol
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["rol" => $this->rolService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Rol
     * @param RolRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(RolRequest $request, int|string $id)
    {
        $this->rolService->modificar($id,$request->all());
        return response()->json(["message" => "Rol modificado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Rol
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->rolService->eliminar($id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Roles
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
        $roles = $this->rolService->queryListado($request->mergeIfMissing(["list_all" => true])->all());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.rol.listado_pdf",compact("roles"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_roles.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Roles
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
            "list_all" => ["nullable", "in:1,0,true,false"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);
        return Excel::download(new RolExport($this->rolService,$request->mergeIfMissing(["list_all" => true])->all()),"reporte_roles.xlsx");
    }
}
