<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\RegistroRecepcionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\RegistroRecepcionRequest;
use App\Services\Api\V1\Dashboard\RegistroRecepcionService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class RegistroRecepcionController extends Controller
{
    protected RegistroRecepcionService $registroRecepcionService;

    public function __construct(RegistroRecepcionService $registroRecepcionService)
    {
        $this->registroRecepcionService = $registroRecepcionService;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using("registro-recepciones-listado"), only: ["index"]),
            new Middleware(PermissionMiddleware::using("registro-recepciones-visualizar"), only: ["show"]),
            new Middleware(PermissionMiddleware::using("registro-recepciones-crear"), only: ["store"]),
            new Middleware(PermissionMiddleware::using("registro-recepciones-modificar"), only: ["update"]),
            new Middleware(PermissionMiddleware::using("registro-recepciones-eliminar"), only: ["destroy", "eliminarDocumento", "eliminarDocumentoRespuesta"]),
            new Middleware(PermissionMiddleware::using("registro-recepciones-reportes"), only: ["reportePdf", "reporteExcel", "fichaRecepcionPdf"]),
        ];
    }

    /**
     * Listar Registros de Recepción
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
            "list_all" => ["nullable", "in:1,0,true,false"],
            "proyecto_id" => ["nullable", "integer", Rule::exists("proyectos", "id")],
            "situacion" => ["nullable", "string", "in:R,SR"],
            "prioridad" => ["nullable", "integer", "in:1,2,3"],
            "fecha_desde" => ["nullable", "date"],
            "fecha_hasta" => ["nullable", "date"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);

        return response()->json(["registro_recepciones" => $this->registroRecepcionService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Registro de Recepción
     * @param RegistroRecepcionRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(RegistroRecepcionRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Procesar documentos adjuntos
            $data["documentos_adjuntos"] = [];
            if ($request->hasFile("documentos_adjuntos")) {
                foreach ($request->file("documentos_adjuntos") as $archivo) {
                    $nombreArchivo = basename($archivo->store("dashboard/registro-recepciones"));
                    $data["documentos_adjuntos"][] = $nombreArchivo;
                }
            }

            // Procesar documentos de respuesta
            $data["documentos_respuesta"] = [];
            if ($request->hasFile("documentos_respuesta")) {
                foreach ($request->file("documentos_respuesta") as $archivo) {
                    $nombreArchivo = basename($archivo->store("dashboard/registro-recepciones"));
                    $data["documentos_respuesta"][] = $nombreArchivo;
                }
            }

            $data["creado_por_usuario_id"] = auth()->user()->id;
            $this->registroRecepcionService->guardar($data);

            DB::commit();
            return response()->json(["message" => "Registro de recepción creado"], ResponseHttpCode::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Error al guardar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Visualizar Registro de Recepción
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["registro_recepcion" => $this->registroRecepcionService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Registro de Recepción
     * @param RegistroRecepcionRequest $request
     * @param int|string $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(RegistroRecepcionRequest $request, int|string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Procesar documentos adjuntos
            $data["documentos_adjuntos"] = [];
            if ($request->hasFile("documentos_adjuntos")) {
                foreach ($request->file("documentos_adjuntos") as $archivo) {
                    $nombreArchivo = basename($archivo->store("dashboard/registro-recepciones"));
                    $data["documentos_adjuntos"][] = $nombreArchivo;
                }
            }

            // Procesar documentos de respuesta
            $data["documentos_respuesta"] = [];
            if ($request->hasFile("documentos_respuesta")) {
                foreach ($request->file("documentos_respuesta") as $archivo) {
                    $nombreArchivo = basename($archivo->store("dashboard/registro-recepciones"));
                    $data["documentos_respuesta"][] = $nombreArchivo;
                }
            }

            $data["modificado_por_usuario_id"] = auth()->user()->id;
            $this->registroRecepcionService->modificar($id, $data);

            DB::commit();
            return response()->json(["message" => "Registro de recepción modificado"], ResponseHttpCode::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Error al modificar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar Registro de Recepción
     * @param int|string $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(int|string $id)
    {
        DB::beginTransaction();
        try {
            $this->registroRecepcionService->eliminar($id, auth()->user()->id);
            DB::commit();
            return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Error al eliminar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Eliminar Documento Adjunto
     * @param int|string $documentoId
     * @return JsonResponse
     */
    public function eliminarDocumento(int|string $documentoId)
    {
        $this->registroRecepcionService->eliminarDocumento($documentoId);
        return response()->json(["message" => "Documento eliminado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Documento de Respuesta
     * @param int|string $documentoId
     * @return JsonResponse
     */
    public function eliminarDocumentoRespuesta(int|string $documentoId)
    {
        $this->registroRecepcionService->eliminarDocumentoRespuesta($documentoId);
        return response()->json(["message" => "Documento de respuesta eliminado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar el estado del documento de la respuesta
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function modificarEstadoRespuesta(Request $request, int|string $id)
    {
        $request->validate([
            "estado_documento" => ["required", "string", "max:100"]
        ], [], [
            "estado_documento" => "estado del documento"
        ]);
        $this->registroRecepcionService->modificarEstadoRespuesta($id,$request->input("estado_documento"));
        return response()->json(["message" => "Estado de documento modificado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar la prioridad del estado respuesta
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function modificarEstadoRespuestaPrioridad(Request $request, int|string $id)
    {
        $request->validate([
            "prioridad" => ["required", "in:1,2,3"]
        ], [], [
            "prioridad" => "estado del documento"
        ]);
        $this->registroRecepcionService->modificarEstadoRespuestaPrioridad($id,$request->input("prioridad"));
        return response()->json(["message" => "Prioridad modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar la situacion del estado respuesta
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function modificarEstadoRespuestaSituacion(Request $request, int|string $id)
    {
        $request->validate([
            "situacion" => ["required", "string", "max:100"]
        ], [], [
            "situacion" => "estado del documento"
        ]);
        $this->registroRecepcionService->modificarEstadoRespuestaSituacion($id,$request->input("situacion"));
        return response()->json(["message" => "Situacion modificada"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Reporte PDF de Listado de Registros de Recepción
     * @param Request $request
     * @return Response
     */
    public function reportePdf(Request $request)
    {
        $request->validate([
            "search" => ["nullable", "string", "max:255"],
            "list_all" => ["nullable", "boolean"]
        ]);

        $registros = $this->registroRecepcionService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.registro_recepcion.listado_pdf", compact("registros"))
            ->setPaper("A4", "landscape");
        return $pdf->download("reporte_registro_recepciones.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Registros de Recepción
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function reporteExcel(Request $request)
    {
        $request->validate([
            "search" => ["nullable", "string", "max:255"],
            "list_all" => ["nullable", "in:1,0,true,false"]
        ]);

        return Excel::download(
            new RegistroRecepcionExport($this->registroRecepcionService, $request->mergeIfMissing(["list_all" => true])->toArray()),
            "reporte_registro_recepciones.xlsx"
        );
    }

    /**
     * Generar Número de Recepción
     * @return JsonResponse
     */
    public function generarCodigoRecepcion()
    {
        return response()->json(["codigo_recepcion" => $this->registroRecepcionService->generarCodigoRecepcion()], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Reporte PDF de Ficha de Recepción
     * @param Request $request
     * @param int|string $id
     * @return Response
     */
    public function fichaRecepcionPdf(Request $request, int|string $id)
    {
        $registro = $this->registroRecepcionService->visualizar($id);
        $pdf = PDF::loadView("api.v1.dashboard.reportes.registro_recepcion.ficha_recepcion", compact("registro"));
        return $pdf->download("ficha_recepcion_{$registro->id}.pdf");
    }
}

