<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\ResponsableExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\ResponsableRequest;
use App\Services\Api\V1\Dashboard\ResponsableService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class ResponsableController extends Controller
{
    protected ResponsableService $responsableService;
    public function __construct(ResponsableService $responsableService)
    {
        $this->responsableService = $responsableService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("responsables-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("responsables-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("responsables-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("responsables-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("responsables-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("responsables-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Responsables
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

        return response()->json(["responsables" => $this->responsableService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Responsable
     * @param ResponsableRequest $request
     * @return JsonResponse
     */
    public function store(ResponsableRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->responsableService->guardar($data);
        return response()->json(["message" => "Responsable creado"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Responsable
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["responsable" => $this->responsableService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Responsable
     * @param ResponsableRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(ResponsableRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->responsableService->modificar($id,$data);
        return response()->json(["message" => "Responsable modificado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Responsable
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->responsableService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Responsables
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
        $responsables = $this->responsableService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.responsable.listado_pdf",compact("responsables"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_responsables.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Responsables
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
        return Excel::download(new ResponsableExport($this->responsableService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_responsables.xlsx");
    }
}
