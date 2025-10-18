<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\EspecialidadExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\EspecialidadRequest;
use App\Services\Api\V1\Dashboard\EspecialidadService;
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

class EspecialidadController extends Controller
{
    protected EspecialidadService $especialidadService;
    public function __construct(EspecialidadService $especialidadService)
    {
        $this->especialidadService = $especialidadService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("especialidades-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("especialidades-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("especialidades-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("especialidades-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("especialidades-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("especialidades-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Especialidades
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

        return response()->json(["especialidades" => $this->especialidadService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Especialidad
     * @param EspecialidadRequest $request
     * @return JsonResponse
     */
    public function store(EspecialidadRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->especialidadService->guardar($data);
        return response()->json(["message" => "Especialidad creada"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Especialidad
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["especialidad" => $this->especialidadService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Especialidad
     * @param EspecialidadRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(EspecialidadRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->especialidadService->modificar($id,$data);
        return response()->json(["message" => "Especialidad modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Especialidad
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->especialidadService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Especialidades
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
        $especialidades = $this->especialidadService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.especialidad.listado_pdf",compact("especialidades"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_especialidades.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Especialidades
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
        return Excel::download(new EspecialidadExport($this->especialidadService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_especialidades.xlsx");
    }
}

