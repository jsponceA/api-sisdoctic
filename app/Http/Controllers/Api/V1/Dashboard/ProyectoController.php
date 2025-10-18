<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\ProyectoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\ProyectoRequest;
use App\Services\Api\V1\Dashboard\ProyectoService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseHttpCode;

class ProyectoController extends Controller
{
    protected ProyectoService $proyectoService;

    public function __construct(ProyectoService $proyectoService)
    {
        $this->proyectoService = $proyectoService;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using("proyectos-listado"), only: ["index"]),
            new Middleware(PermissionMiddleware::using("proyectos-visualizar"), only: ["show"]),
            new Middleware(PermissionMiddleware::using("proyectos-crear"), only: ["store"]),
            new Middleware(PermissionMiddleware::using("proyectos-modificar"), only: ["update"]),
            new Middleware(PermissionMiddleware::using("proyectos-eliminar"), only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("proyectos-reportes"), only: ["reportePdf", "reporteExcel", "fichaProyectoPdf"]),
        ];
    }

    /**
     * Listar Proyectos
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
            "responsable_id" => ["nullable", "integer", Rule::exists("responsables", "id")],
            "estado" => ["nullable", "string"],
            "fecha_desde" => ["nullable", "date"],
            "fecha_hasta" => ["nullable", "date"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);

        return response()->json(["proyectos" => $this->proyectoService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Proyecto
     * @param ProyectoRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(ProyectoRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data["documentos"] = [];
            $data["fotografias"] = [];

            foreach ($request->file("documentos", []) ?? [] as $archivo) {
                $data["documentos"][] = basename($archivo->store("dashboard/proyectos"));
            }

            foreach ($request->file("fotografias", []) ?? [] as $foto) {
                $data["fotografias"][] = basename($foto->store("dashboard/proyectos"));
            }

            $data["creado_por_usuario_id"] = auth()->user()->id;
            $this->proyectoService->guardar($data);

            DB::commit();
            return response()->json(["message" => "Proyecto creado"], ResponseHttpCode::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al guardar: {$e->getMessage()} - {$e->getFile()} - {$e->getLine()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Visualizar Proyecto
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["proyecto" => $this->proyectoService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Proyecto
     * @param ProyectoRequest $request
     * @param int|string $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(ProyectoRequest $request, int|string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data["documentos"] = [];
            $data["fotografias"] = [];

            foreach ($request->file("documentos", []) ?? [] as $archivo) {
                $data["documentos"][] = basename($archivo->store("dashboard/proyectos"));
            }

            foreach ($request->file("fotografias", []) ?? [] as $foto) {
                $data["fotografias"][] = basename($foto->store("dashboard/proyectos"));
            }

            $data["modificado_por_usuario_id"] = auth()->user()->id;
            $this->proyectoService->modificar($id, $data);

            DB::commit();
            return response()->json(["message" => "Proyecto modificado"], ResponseHttpCode::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al modificar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar Proyecto
     * @param int|string $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(int|string $id)
    {
       DB::beginTransaction();
        try {
            $this->proyectoService->eliminar($id, auth()->user()->id);
            DB::commit();
            return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrió el siguiente error al eliminar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reporte PDF de Listado de Proyectos
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

        $proyectos = $this->proyectoService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.proyecto.listado_pdf", compact("proyectos"))
            ->setPaper("A4", "landscape")
            ->setOptions([
                'encoding' => 'UTF-8',
                'isRemoteEnabled' => true,
            ]);
        return $pdf->download("reporte_proyectos.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Proyectos
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
            new ProyectoExport($this->proyectoService, $request->mergeIfMissing(["list_all" => true])->toArray()),
            "reporte_proyectos.xlsx"
        );
    }

    /**
     * Generar Código de Proyecto
     * @return JsonResponse
     */
    public function generarCodigoProyecto()
    {
        return response()->json(["codigo_proyecto" => $this->proyectoService->generarCodigoProyecto()], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Reporte PDF de Ficha de Proyecto
     * @param Request $request
     * @param int|string $id
     * @return Response
     */
    public function fichaProyectoPdf(Request $request, int|string $id)
    {
        $proyecto = $this->proyectoService->visualizar($id);

        $pdf = PDF::loadView("api.v1.dashboard.reportes.proyecto.ficha_proyecto", compact("proyecto"));
        return $pdf->download("ficha_proyecto_{$proyecto->codigo_proyecto}.pdf");
    }
}

