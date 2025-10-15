<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Exports\Api\V1\Dashboard\ArticuloExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Dashboard\ArticuloRequest;
use App\Services\Api\V1\Dashboard\ArticuloService;
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

class ArticuloController extends Controller
{
    protected ArticuloService $articuloService;
    public function __construct(ArticuloService $articuloService)
    {
        $this->articuloService = $articuloService;
    }

    public static function middleware(){
        return [
            new Middleware(PermissionMiddleware::using("articulos-listado"),only: ["index"]),
            new Middleware(PermissionMiddleware::using("articulos-visualizar"),only: ["show"]),
            new Middleware(PermissionMiddleware::using("articulos-crear"),only: ["store"]),
            new Middleware(PermissionMiddleware::using("articulos-modificar"),only: ["update"]),
            new Middleware(PermissionMiddleware::using("articulos-eliminar"),only: ["destroy"]),
            new Middleware(PermissionMiddleware::using("articulos-reportes"),only: ["reportePdf","reporteExcel"]),
        ];
    }

    /**
     * Listar Artículos
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
            "tipo_material_id" => ["nullable", "integer", Rule::exists("tipo_materiales","id")],
            "denominacion_id" => ["nullable", "integer", Rule::exists("denominaciones","id")],
            "estado_conservacion_id" => ["nullable", "integer", Rule::exists("estado_conservaciones","id")],
            "responsable_id" => ["nullable", "integer", Rule::exists("responsables","id")],
            "sala_id" => ["nullable", "integer", Rule::exists("salas","id")],
            "estado_integridad" => ["nullable", "in:1,0,true,false"],
            "fecha_desde" => ["nullable"],
            "fecha_hasta" => ["nullable"]
        ], [], [
            "search" => "buscar",
            "per_page" => "cantidad por página",
            "take" => "cantidad a tomar",
            "current_page" => "página actual",
            "list_all" => "listar todos"
        ]);

        return response()->json(["articulos" => $this->articuloService->queryListado($request->toArray())], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Guardar Artículo
     * @param ArticuloRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(ArticuloRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->file("foto_registro_inicial")){
                $data["foto_registro_inicial"] = basename($request->file("foto_registro_inicial")->store("dashboard/articulos"));
            }else{
                unset($data["foto_registro_inicial"]);
            }
            if ($request->file("foto_registro_final")){
                $data["foto_registro_final"] = basename($request->file("foto_registro_final")->store("dashboard/articulos"));
            }else{
                unset($data["foto_registro_final"]);
            }
            if ($request->file("foto_proceso_inicial")) {
                $data["foto_proceso_inicial"] = basename($request->file("foto_proceso_inicial")->store("dashboard/articulos"));
            }else{
                unset($data["foto_proceso_inicial"]);
            }
            if ($request->file("foto_proceso_final")){
                $data["foto_proceso_final"] = basename($request->file("foto_proceso_final")->store("dashboard/articulos"));
            }else{
                unset($data["foto_proceso_final"]);
            }
            $data["creado_por_usuario_id"] = auth()->user()->id;
            $this->articuloService->guardar($data);

            DB::commit();
            return response()->json(["message" => "Artículo creado"], ResponseHttpCode::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrio el siguiente error al guardar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Visualizar Artículo
     * @param int|string $id
     * @return JsonResponse
     */
    public function show(int|string $id)
    {
        return response()->json(["articulo" => $this->articuloService->visualizar($id)], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Modificar Artículo
     * @param ArticuloRequest $request
     * @param int|string $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(ArticuloRequest $request, int|string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->file("foto_registro_inicial")){
                $data["foto_registro_inicial"] = basename($request->file("foto_registro_inicial")->store("dashboard/articulos"));
            }else{
                unset($data["foto_registro_inicial"]);
            }
            if ($request->file("foto_registro_final")){
                $data["foto_registro_final"] = basename($request->file("foto_registro_final")->store("dashboard/articulos"));
            }else{
                unset($data["foto_registro_final"]);
            }
            if ($request->file("foto_proceso_inicial")){
                $data["foto_proceso_inicial"] = basename($request->file("foto_proceso_inicial")->store("dashboard/articulos"));
            }else{
                unset($data["foto_proceso_inicial"]);
            }
            if ($request->file("foto_proceso_final")){
                $data["foto_proceso_final"] = basename($request->file("foto_proceso_final")->store("dashboard/articulos"));
            }else{
                unset($data["foto_proceso_final"]);
            }
            $data["modificado_por_usuario_id"] = auth()->user()->id;
            $this->articuloService->modificar($id,$data);

            DB::commit();
            return response()->json(["message" => "Artículo modificado"], ResponseHttpCode::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Ocurrio el siguiente error al modificar: {$e->getMessage()}"], ResponseHttpCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar Artículo
     * @param int|string $id
     * @return JsonResponse
     */
    public function destroy(int|string $id)
    {
        $this->articuloService->eliminar($id,auth()->user()->id);
        return response()->json(null, ResponseHttpCode::HTTP_NO_CONTENT);
    }

    /**
     * Reporte PDF de Listado de Artículos
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
        $articulos = $this->articuloService->queryListado($request->mergeIfMissing(["list_all" => true])->toArray());
        $pdf = PDF::loadView("api.v1.dashboard.reportes.articulo.listado_pdf",compact("articulos"))->setPaper("A4","landscape")->setOptions([
            'encoding' => 'UTF-8',
            'isRemoteEnabled' => true,
        ]);
        return $pdf->download("reporte_articulos.pdf");
    }

    /**
     * Reporte EXCEL de Listado de Artículos
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
        return Excel::download(new ArticuloExport($this->articuloService,$request->mergeIfMissing(["list_all" => true])->toArray()),"reporte_articulos.xlsx");
    }

    /**
     * Generar Número de Ficha
     * @return JsonResponse
     */
    public function generarNumeroFicha()
    {
        return response()->json(["numero_ficha" => $this->articuloService->generarNumeroFicha()], ResponseHttpCode::HTTP_OK);
    }

    /**
     * Reporte PDF de Ficha de Conversación Preventiva
     * @param Request $request
     * @param int|string $id
     * @return Response
     */
    public function fichaConservacionPdf(Request $request, int|string $id)
    {
        $articulo = $this->articuloService->visualizar($id);

        $pdf = PDF::loadView("api.v1.dashboard.reportes.articulo.ficha_conservacion_preventiva",compact("articulo"));
        return $pdf->download("reporte_ficha_conservacion_preventiva.pdf");
    }
}
