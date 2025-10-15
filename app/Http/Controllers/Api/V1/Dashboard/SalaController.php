<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\SalaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\SalaRequest;
use App\Services\Api\V1\Dashboard\SalaService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class SalaController extends Controller
{
    protected SalaService $salaService;
    public function __construct(SalaService $salaService)
    {
        $this->salaService = $salaService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("salas-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("salas-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("salas-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("salas-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("salas-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("salas-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Salas
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

        return response()->json(["salas" => $this->salaService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Sala
     * @param SalaRequest $request
     * @return JsonResponse
     */
    public function store(SalaRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->salaService->guardar($data);
        return response()->json(["message" => "Sala creada"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Sala
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["sala" => $this->salaService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Sala
     * @param SalaRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(SalaRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->salaService->modificar($id,$data);
        return response()->json(["message" => "Sala modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Sala
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->salaService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Salas
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
        $salas = $this->salaService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.sala.listado_pdf",compact("salas"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_salas.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Salas
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
        return Excel::download(new SalaExport($this->salaService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_salas.xlsx");
    }
}
