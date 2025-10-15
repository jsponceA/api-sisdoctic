<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Articulo;
use App\Models\ArticuloResponsable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticuloService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower();
        $tipoMaterialId = $request->get("tipo_material_id");
        $denominacionId = $request->get("denominacion_id");
        $estadoConservacionId = $request->get("estado_conservacion_id");
        $responsableId = $request->get("responsable_id");
        $salaId = $request->get("sala_id");
        $estadoIntegridad = $request->get("estado_integridad");
        $fechaDesde = $request->get("fecha_desde");
        $fechaHasta = $request->get("fecha_hasta");
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Articulo::query()
            ->with([
                "creadoPor","modificadoPor","eliminadoPor",
                "tipoMaterial","denominacion","estadoConservacion",
                "responsables","sala"
            ])
            ->when(!empty($search),fn($q)=>$q->where(function($query) use ($search) {
                $query->where(DB::raw("LOWER(numero_ficha)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(codigo_inventario_objeto)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(numero_registro_nacional)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(descripcion_objeto)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(precedencia_sitio)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(numero_vitrina)"),"LIKE","%{$search}%");
            }))
            ->when(!empty($tipoMaterialId),fn($q) => $q->where("tipo_material_id",$tipoMaterialId))
            ->when(!empty($denominacionId),fn($q) => $q->where("denominacion_id",$denominacionId))
            ->when(!empty($estadoConservacionId),fn($q) => $q->where("estado_conservacion_id",$estadoConservacionId))
            ->when(!empty($responsableId),fn($q) => $q->whereHas("responsables",fn($q) => $q->where("responsable_id",$responsableId)))
            ->when(!empty($salaId),fn($q) => $q->where("sala_id",$salaId))
            ->when(!empty($estadoIntegridad),fn($q) => $q->where("estado_integridad",$estadoIntegridad))
            ->when(!empty($fechaDesde),fn($q) => $q->whereDate("fecha_fin",">=",$fechaDesde))
            ->when(!empty($fechaHasta),fn($q) => $q->whereDate("fecha_fin","<=",$fechaHasta))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $articulo = new Articulo();
        $articulo->fill($data->except("responsables")->toArray());
        $articulo->numero_ficha = $this->generarNumeroFicha();
        $articulo->save();

        foreach ($data->get("responsables",[]) as $responsableId) {
            ArticuloResponsable::query()->create([
                "articulo_id" => $articulo->id,
                "responsable_id" => $responsableId,
            ]);
        }

        return $articulo;
    }

    public function visualizar(int | string $id)
    {
        return Articulo::query()
            ->with([
                "tipoMaterial","denominacion","estadoConservacion",
                "responsables","sala","creadoPor","modificadoPor","eliminadoPor"
            ])
            ->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $articulo = Articulo::query()->findOrFail($id);
        if (!empty($data->get("foto_registro_inicial"))){
            Storage::delete("dashboard/articulos/{$articulo->foto_registro_inicial}");
        }
        if (!empty($data->get("foto_registro_final"))){
            Storage::delete("dashboard/articulos/{$articulo->foto_registro_final}");
        }
        if (!empty($data->get("foto_proceso_inicial"))){
            Storage::delete("dashboard/articulos/{$articulo->foto_proceso_inicial}");
        }
        if (!empty($data->get("foto_proceso_final"))){
            Storage::delete("dashboard/articulos/{$articulo->foto_proceso_final}");
        }
        $articulo->fill($data->except("numero_ficha")->toArray());
        $articulo->update();

        ArticuloResponsable::query()->where("articulo_id",$articulo->id)->delete();
        foreach ($data->get("responsables",[]) as $responsableId) {
            ArticuloResponsable::query()->create([
                "articulo_id" => $articulo->id,
                "responsable_id" => $responsableId,
            ]);
        }

        return $articulo;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $articulo = Articulo::query()->findOrFail($id);
        Storage::delete("dashboard/articulos/{$articulo->foto_registro_inicial}");
        Storage::delete("dashboard/articulos/{$articulo->foto_registro_final}");
        Storage::delete("dashboard/articulos/{$articulo->foto_proceso_inicial}");
        Storage::delete("dashboard/articulos/{$articulo->foto_proceso_final}");
        $articulo->eliminado_por_usuario_id = $userId;
        $articulo->update();
        $articulo->delete();
        return $articulo;
    }

    public function generarNumeroFicha()
    {
        $cantidadRegistros = Articulo::query()->count();
        $nuevoNumero = $cantidadRegistros + 1;
        return Str::padLeft($nuevoNumero,8,"0");
    }


}
