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
            new Middleware(PermissionMiddleware::using("registro-recepciones-eliminar"), only: ["destroy", "eliminarDocumento", "eliminarImagen"]),
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
            "responsable_id" => ["nullable", "integer", Rule::exists("responsables", "id")],
            "tipo_recepcion" => ["nullable", "string"],
            "estado_proceso" => ["nullable", "string"],
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

            // Procesar documentos
            $data["documentos"] = [];
            if ($request->has("documentos")) {
                foreach ($request->input("documentos", []) as $index => $documento) {
                    $archivoKey = "documentos.{$index}.archivo";
                    $archivoGuardado = null;

                    if ($request->hasFile($archivoKey)) {
                        $archivo = $request->file($archivoKey);
                        $archivoGuardado = basename($archivo->store("dashboard/registro-recepciones"));
                    }

                    $data["documentos"][] = [
                        "archivo" => $archivoGuardado,
                        "nombre_documento" => $documento['nombre_documento'] ?? null,
                        "tipo_documento" => $documento['tipo_documento'] ?? null,
                    ];
                }
            }

            // Procesar imágenes
            $data["imagenes"] = [];
            if ($request->has("imagenes")) {
                foreach ($request->input("imagenes", []) as $index => $imagen) {
                    $fotoKey = "imagenes.{$index}.foto";
                    $fotoGuardada = null;

                    if ($request->hasFile($fotoKey)) {
                        $foto = $request->file($fotoKey);
                        $fotoGuardada = basename($foto->store("dashboard/registro-recepciones"));
                    }

                    $data["imagenes"][] = [
                        "foto" => $fotoGuardada,
                        "descripcion_foto" => $imagen['descripcion_foto'] ?? null,
                        "tipo_foto" => $imagen['tipo_foto'] ?? null,
                    ];
                }
            }

            $data["creado_por_usuario_id"] = auth()->user()->id;
            $this->registroRecepcionService->guardar($data);

            DB::commit();
            return response()->json(["message" => "Registro de recepción creado"], ResponseHttpCode::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al guardar: {$e->getMessage()} - {$e->getFile()} - {$e->getLine()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
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

            // Procesar documentos
            $data["documentos"] = [];
            if ($request->has("documentos")) {
                foreach ($request->input("documentos", []) as $index => $documento) {
                    $archivoKey = "documentos.{$index}.archivo";
                    $archivoGuardado = null;

                    if ($request->hasFile($archivoKey)) {
                        $archivo = $request->file($archivoKey);
                        $archivoGuardado = basename($archivo->store("dashboard/registro-recepciones"));
                    }

                    if ($archivoGuardado) {
                        $data["documentos"][] = [
                            "archivo" => $archivoGuardado,
                            "nombre_documento" => $documento['nombre_documento'] ?? null,
                            "tipo_documento" => $documento['tipo_documento'] ?? null,
                        ];
                    }
                }
            }

            // Procesar imágenes
            $data["imagenes"] = [];
            if ($request->has("imagenes")) {
                foreach ($request->input("imagenes", []) as $index => $imagen) {
                    $fotoKey = "imagenes.{$index}.foto";
                    $fotoGuardada = null;

                    if ($request->hasFile($fotoKey)) {
                        $foto = $request->file($fotoKey);
                        $fotoGuardada = basename($foto->store("dashboard/registro-recepciones"));
                    }

                    if ($fotoGuardada) {
                        $data["imagenes"][] = [
                            "foto" => $fotoGuardada,
                            "descripcion_foto" => $imagen['descripcion_foto'] ?? null,
                            "tipo_foto" => $imagen['tipo_foto'] ?? null,
                        ];
                    }
                }
            }

            $data["modificado_por_usuario_id"] = auth()->user()->id;
            $this->registroRecepcionService->modificar($id, $data);

            DB::commit();
            return response()->json(["message" => "Registro de recepción modificado"], ResponseHttpCode::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al modificar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar Registro de Recepción
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->registroRecepcionService->eliminar($id, auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Eliminar Documento de Registro de Recepción
     * @param int|string $documentoId
     * @return JsonResponse
     */
    public function eliminarDocumento(int|string $documentoId)
    {
        $this->registroRecepcionService->eliminarDocumento($documentoId);
        return response()->json(["message" => "Documento eliminado"], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Eliminar Imagen de Registro de Recepción
     * @param int|string $imagenId
     * @return JsonResponse
     */
    public function eliminarImagen(int|string $imagenId)
    {
        $this->registroRecepcionService->eliminarImagen($imagenId);
        return response()->json(["message" => "Imagen eliminada"], ResponseHttpCode::HTTP_OK);
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

        $registros = $this->registroRecepcionService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.registro_recepcion.listado_pdf", compact("registros"))
            ->setPaper("A4", "landscape")
            ->setOptions([
                'encoding' => 'UTF-8',
                'isRemoteEnabled' => true,
            ]);
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
        return $pdf->download("ficha_recepcion_{$registro->numero_recepcion}.pdf");
    }
}

