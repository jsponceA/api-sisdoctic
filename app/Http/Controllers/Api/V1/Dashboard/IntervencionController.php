<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\IntervencionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\IntervencionRequest;
use App\Services\Api\V1\Dashboard\IntervencionService;
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

class IntervencionController extends Controller
{
    protected IntervencionService $intervencionService;

    public function __construct(IntervencionService $intervencionService)
    {
        $this->intervencionService = $intervencionService;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using("intervenciones-listado"), only: ["index"]),
            new Middleware(PermissionMiddleware::using("intervenciones-visualizar"), only: ["show"]),
            new Middleware(PermissionMiddleware::using("intervenciones-crear"), only: ["store"]),
            new Middleware(PermissionMiddleware::using("intervenciones-modificar"), only: ["update"]),
            new Middleware(PermissionMiddleware::using("intervenciones-eliminar"), only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("intervenciones-reportes"), only: ["reportePdf", "reporteExcel", "fichaIntervencionPdf"]),
        ];
    }

    /**
     * Listar Intervenciones
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
            "categoria_id" => ["nullable", "integer", Rule::exists("categorias", "id")],
            "denominacion_id" => ["nullable", "integer", Rule::exists("denominaciones", "id")],
            "responsable_id" => ["nullable", "integer", Rule::exists("responsables", "id")],
            "fecha_desde" => ["nullable"],
            "fecha_hasta" => ["nullable"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);

        return response()->json(["intervenciones" => $this->intervencionService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Intervención
     * @param IntervencionRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(IntervencionRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data["vista_generales"] = [];
            $data["vista_detalles"] = [];
            $data["vista_intervenciones"] = [];

            foreach ($request->file("vista_generales",[]) ?? [] as $foto) {
                $data["vista_generales"][] = basename($foto->store("dashboard/intervenciones"));
            }

            foreach ($request->file("vista_detalles",[]) ?? [] as $foto) {
                $data["vista_detalles"][] = basename($foto->store("dashboard/intervenciones"));
            }

            foreach ($request->file("vista_intervenciones",[]) ?? [] as $foto) {
                $data["vista_intervenciones"][] = basename($foto->store("dashboard/intervenciones"));
            }

            $data["creado_por_usuario_id"] = auth()->user()->id;
            $this->intervencionService->guardar($data);

            DB::commit();
            return response()->json(["message" => "Intervención creada"], ResponseHttpCode::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al guardar: {$e->getMessage()} - {$e->getFile()} - {$e->getLine()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Visualizar Intervención
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["intervencion" => $this->intervencionService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Intervención
     * @param IntervencionRequest $request
     * @param int|string $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(IntervencionRequest $request, int|string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data["vista_generales"] = [];
            $data["vista_detalles"] = [];
            $data["vista_intervenciones"] = [];

            foreach ($request->file("vista_generales",[]) ?? [] as $foto) {
                $data["vista_generales"][] = basename($foto->store("dashboard/intervenciones"));
            }

            foreach ($request->file("vista_detalles",[]) ?? [] as $foto) {
                $data["vista_detalles"][] = basename($foto->store("dashboard/intervenciones"));
            }

            foreach ($request->file("vista_intervenciones",[]) ?? [] as $foto) {
                $data["vista_intervenciones"][] = basename($foto->store("dashboard/intervenciones"));
            }

            $data["modificado_por_usuario_id"] = auth()->user()->id;
            $this->intervencionService->modificar($id, $data);

            DB::commit();
            return response()->json(["message" => "Intervención modificada"], ResponseHttpCode::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al modificar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar Intervención
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->intervencionService->eliminar($id, auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Intervenciones
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

        $intervenciones = $this->intervencionService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.intervencion.listado_pdf", compact("intervenciones"))
            ->setPaper("A4", "landscape")
            ->setOptions([
                'encoding' => 'UTF-8',
                'isRemoteEnabled' => true,
            ]);
        return $pdf->download("reporte_intervenciones.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Intervenciones
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
            new IntervencionExport($this->intervencionService, $request->mergeIfMissing(["list_all" => true])->toArray()),
            "reporte_intervenciones.xlsx"
        );
    }

    /**
     * Generar Número de Ficha
     * @return JsonResponse
     */
    public function generarNumeroFicha()
    {
        return response()->json(["numero_ficha" => $this->intervencionService->generarNumeroFicha()], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Reporte PDF de Ficha de Intervención
     * @param Request $request
     * @param int|string $id
     * @return Response
     */
    public function fichaIntervencionPdf(Request $request, int|string $id)
    {
        $intervencion = $this->intervencionService->visualizar($id);

        $pdf = PDF::loadView("api.v1.dashboard.reportes.intervencion.ficha_intervencion", compact("intervencion"));
        return $pdf->download("ficha_intervencion_{$intervencion->numero_ficha}.pdf");
    }
}

