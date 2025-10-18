<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\TipoDocumentoClasificacionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\TipoDocumentoClasificacionRequest;
use App\Services\Api\V1\Dashboard\TipoDocumentoClasificacionService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class TipoDocumentoClasificacionController extends Controller
{
    protected TipoDocumentoClasificacionService $tipoDocumentoClasificacionService;
    public function __construct(TipoDocumentoClasificacionService $tipoDocumentoClasificacionService)
    {
        $this->tipoDocumentoClasificacionService = $tipoDocumentoClasificacionService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("tipos-documento-clasificacion-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("tipos-documento-clasificacion-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("tipos-documento-clasificacion-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("tipos-documento-clasificacion-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("tipos-documento-clasificacion-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("tipos-documento-clasificacion-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Tipos de Documento de Clasificación
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

        return response()->json(["tipos_documento_clasificacion" => $this->tipoDocumentoClasificacionService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Tipo de Documento de Clasificación
     * @param TipoDocumentoClasificacionRequest $request
     * @return JsonResponse
     */
    public function store(TipoDocumentoClasificacionRequest $request)
    {
        $data = $request->validated();
        $data["creado_por_usuario_id"] = auth()->user()->id;
        $this->tipoDocumentoClasificacionService->guardar($data);
        return response()->json(["message" => "Tipo de documento de clasificación creado"], ResponseHttpCode::HTTP_CREATED);
    }

    /**
     * Visualizar Tipo de Documento de Clasificación
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["tipo_documento_clasificacion" => $this->tipoDocumentoClasificacionService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Tipo de Documento de Clasificación
     * @param TipoDocumentoClasificacionRequest $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(TipoDocumentoClasificacionRequest $request, int|string $id)
    {
        $data = $request->validated();
        $data["modificado_por_usuario_id"] = auth()->user()->id;
        $this->tipoDocumentoClasificacionService->modificar($id,$data);
        return response()->json(["message" => "Tipo de documento de clasificación modificado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Tipo de Documento de Clasificación
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->tipoDocumentoClasificacionService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Tipos de Documento de Clasificación
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
        $tipos_documento_clasificacion = $this->tipoDocumentoClasificacionService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.tipo_documento_clasificacion.listado_pdf",compact("tipos_documento_clasificacion"))->setOptions([
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'encoding' => 'UTF-8',
            'footer-right' => 'Página [page] de [topage]',
            'footer-font-size' => 9,
        ]);
        return $pdf->download("reporte_tipos_documento_clasificacion.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Tipos de Documento de Clasificación
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
        return Excel::download(new TipoDocumentoClasificacionExport($this->tipoDocumentoClasificacionService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_tipos_documento_clasificacion.xlsx");
    }
}

