<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\TipoMaterialExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\TipoMaterialRequest;
use App\Services\Api\V1\Dashboard\TipoMaterialService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class TipoMaterialController extends Controller
{
    protected TipoMaterialService $tipoMaterialService;
    public function __construct(TipoMaterialService $tipoMaterialService)
    {
        $this->tipoMaterialService = $tipoMaterialService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("tipo-materiales-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("tipo-materiales-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("tipo-materiales-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("tipo-materiales-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("tipo-materiales-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("tipo-materiales-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Tipos de Materiales
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

        return response()->json(["tipo_materiales" => $this->tipoMaterialService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Tipo de Material
     * @param TipoMaterialRequest $request
     * @return JsonResponse
     */
    public function store(TipoMaterialRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->tipoMaterialService->guardar($data);
        return response()->json(["message" => "Tipo de Material creado"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Tipo de Material
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["tipo_material" => $this->tipoMaterialService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Tipo de Material
     * @param TipoMaterialRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(TipoMaterialRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->tipoMaterialService->modificar($id,$data);
        return response()->json(["message" => "Tipo de Material modificado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Tipo de Material
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->tipoMaterialService->eliminar($id, auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Tipos de Materiales
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
        $tipoMateriales = $this->tipoMaterialService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.tipo_material.listado_pdf",compact("tipoMateriales"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_tipo_materiales.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Tipos de Materiales
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
        return Excel::download(new TipoMaterialExport($this->tipoMaterialService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_tipo_materiales.xlsx");
    }
}
