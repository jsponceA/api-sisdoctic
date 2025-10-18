<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\AreaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\AreaRequest;
use App\Services\Api\V1\Dashboard\AreaService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class AreaController extends Controller
{
    protected AreaService $areaService;
    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("areas-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("areas-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("areas-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("areas-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("areas-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("areas-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Areas
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

        return response()->json(["areas" => $this->areaService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Area
     * @param AreaRequest $request
     * @return JsonResponse
     */
    public function store(AreaRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->areaService->guardar($data);
        return response()->json(["message" => "Área creada"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Area
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["area" => $this->areaService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Area
     * @param AreaRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(AreaRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->areaService->modificar($id,$data);
        return response()->json(["message" => "Área modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Area
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->areaService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Areas
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
        $areas = $this->areaService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.area.listado_pdf",compact("areas"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_areas.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Areas
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
        return Excel::download(new AreaExport($this->areaService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_areas.xlsx");
    }
}

