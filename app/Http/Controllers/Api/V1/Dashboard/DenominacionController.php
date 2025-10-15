<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\DenominacionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\DenominacionRequest;
use App\Services\Api\V1\Dashboard\DenominacionService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class DenominacionController extends Controller
{
    protected DenominacionService $denominacionService;
    public function __construct(DenominacionService $denominacionService)
    {
        $this->denominacionService = $denominacionService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("denominaciones-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("denominaciones-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("denominaciones-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("denominaciones-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("denominaciones-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("denominaciones-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Denominaciones
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


        return response()->json(["denominaciones" => $this->denominacionService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Denominacion
     * @param DenominacionRequest $request
     * @return JsonResponse
     */
    public function store(DenominacionRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->denominacionService->guardar($data);
        return response()->json(["message" => "Denominacion creada"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Denominacion
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["denominacion" => $this->denominacionService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Denominacion
     * @param DenominacionRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(DenominacionRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->denominacionService->modificar($id,$data);
        return response()->json(["message" => "Denominacion modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Denominacion
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->denominacionService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Denominaciones
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
        $denominaciones = $this->denominacionService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.denominacion.listado_pdf",compact("denominaciones"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_denominaciones.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Denominaciones
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
        return Excel::download(new DenominacionExport($this->denominacionService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_denominaciones.xlsx");
    }
}
