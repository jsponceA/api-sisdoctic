<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\CategoriaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\CategoriaRequest;
use App\Services\Api\V1\Dashboard\CategoriaService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class CategoriaController extends Controller
{
    protected CategoriaService $categoriaService;

    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using("categorias-listado"), only: ["index"]),
            new Middleware(PermissionMiddleware::using("categorias-visualizar"), only: ["show"]),
            new Middleware(PermissionMiddleware::using("categorias-crear"), only: ["store"]),
            new Middleware(PermissionMiddleware::using("categorias-modificar"), only: ["update"]),
            new Middleware(PermissionMiddleware::using("categorias-eliminar"), only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("categorias-reportes"), only: ["reportePdf", "reporteExcel"]),
        ];
    }

    /**
     * Listar Categorías
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

        return response()->json(["categorias" => $this->categoriaService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Categoría
     * @param CategoriaRequest $request
     * @return JsonResponse
     */
    public function store(CategoriaRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->categoriaService->guardar($data);
        return response()->json(["message" => "Categoría creada"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Categoría
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["categoria" => $this->categoriaService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Categoría
     * @param CategoriaRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(CategoriaRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->categoriaService->modificar($id, $data);
        return response()->json(["message" => "Categoría modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Categoría
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->categoriaService->eliminar($id, auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Categorías
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

        $categorias = $this->categoriaService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.categoria.listado_pdf", compact("categorias"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_categorias.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Categorías
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

        return Excel::download(
            new CategoriaExport($this->categoriaService, $request->mergeIfMissing(["list_all" => true])->toArray()),
            "reporte_categorias.xlsx"
        );
    }
}

