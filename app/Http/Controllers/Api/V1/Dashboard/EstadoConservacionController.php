<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\EstadoConservacionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\EstadoConservacionRequest;
use App\Services\Api\V1\Dashboard\EstadoConservacionService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class EstadoConservacionController extends Controller
{
    protected EstadoConservacionService $estadoConservacionService;
    public function __construct(EstadoConservacionService $estadoConservacionService)
    {
        $this->estadoConservacionService = $estadoConservacionService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("estados-conservacion-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("estados-conservacion-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("estados-conservacion-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("estados-conservacion-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("estados-conservacion-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("estados-conservacion-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Estados de Conservación
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

        return response()->json(["estados_conservacion" => $this->estadoConservacionService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Estado de Conservación
     * @param EstadoConservacionRequest $request
     * @return JsonResponse
     */
    public function store(EstadoConservacionRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->estadoConservacionService->guardar($data);
        return response()->json(["message" => "Estado de Conservación creado"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Estado de Conservación
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["estado_conservacion" => $this->estadoConservacionService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Estado de Conservación
     * @param EstadoConservacionRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(EstadoConservacionRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->estadoConservacionService->modificar($id,$data);
        return response()->json(["message" => "Estado de Conservación modificado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Estado de Conservación
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->estadoConservacionService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Estados de Conservación
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
        $estadoConservaciones = $this->estadoConservacionService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.estado_conservacion.listado_pdf",compact("estadoConservaciones"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_estados-conservacion.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Estados de Conservación
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
        return Excel::download(new EstadoConservacionExport($this->estadoConservacionService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_estados-conservacion.xlsx");
    }
}
