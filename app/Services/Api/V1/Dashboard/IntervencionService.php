<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Intervencion;
use App\Models\IntervencionProceso;
use App\Models\IntervencionVistaDetalle;
use App\Models\IntervencionVistaGeneral;
use App\Models\IntervencionVistaIntervencion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IntervencionService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $categoriaId = $request->get("categoria_id");
        $denominacionId = $request->get("denominacion_id");
        $responsableId = $request->get("responsable_id");
        $fechaDesde = $request->get("fecha_desde");
        $fechaHasta = $request->get("fecha_hasta");
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all", false), FILTER_VALIDATE_BOOLEAN);

        return Intervencion::query()
            ->with([
                "creadoPor", "modificadoPor", "eliminadoPor",
                "categoria", "denominacion", "directorEncargado", "conservadorResponsable",
                "procesos", "vistaGenerales", "vistaDetalles", "vistaIntervenciones"
            ])
            ->when(!empty($search), fn($q) => $q->where(function ($query) use ($search) {
                $query->where(DB::raw("LOWER(numero_ficha)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(numero_registro_nacional)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(numero_inventario)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(cultura_estilo_autor)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(procedencia)"), "LIKE", "%{$search}%");
            }))
            ->when(!empty($categoriaId), fn($q) => $q->where("categoria_id", $categoriaId))
            ->when(!empty($denominacionId), fn($q) => $q->where("denominacion_id", $denominacionId))
            ->when(!empty($responsableId), fn($q) => $q->where(function ($query) use ($responsableId) {
                $query->where("director_encargado_id", $responsableId)
                    ->orWhere("conservador_responsable_id", $responsableId);
            }))
            ->when(!empty($fechaDesde), fn($q) => $q->whereDate("fecha_entrega", ">=", $fechaDesde))
            ->when(!empty($fechaHasta), fn($q) => $q->whereDate("fecha_entrega", "<=", $fechaHasta))
            ->orderBy("id", "DESC")
            ->when($listAll, fn($q) => $q->when(!empty($take) && $take > 0, fn($q) => $q->take($take))->get())
            ->when(!$listAll, fn($q) => $q->paginate($perPage, "*", "page", $currentPage));
    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $intervencion = new Intervencion();
        $intervencion->fill($data->except("procesos", "vista_generales", "vista_detalles", "vista_intervenciones")->toArray());
        $intervencion->numero_ficha = $this->generarNumeroFicha();
        $intervencion->save();

        // Guardar procesos de intervención
        foreach ($data->get("procesos", []) ?? [] as $proceso) {
            IntervencionProceso::query()->create([
                "intervencion_id" => $intervencion->id,
                "area_material_intervenido" => $proceso['area_material_intervenido'] ?? null,
                "intervencion_tratamiento" => $proceso['intervencion_tratamiento'] ?? null,
                "insumo_producto_herramiental" => $proceso['insumo_producto_herramiental'] ?? null,
                "procedimiento" => $proceso['procedimiento'] ?? null,
            ]);
        }

        // Guardar vistas generales
        foreach ($data->get("vista_generales", []) ?? [] as $foto) {
            IntervencionVistaGeneral::query()->create([
                "intervencion_id" => $intervencion->id,
                "foto" => $foto,
            ]);
        }
        // Guardar vistas de detalle
        foreach ($data->get("vista_detalles", []) ?? [] as $foto) {
            IntervencionVistaDetalle::query()->create([
                "intervencion_id" => $intervencion->id,
                "foto" => $foto,
            ]);
        }

        // Guardar vistas de intervención
        foreach ($data->get("vista_intervenciones", []) ?? [] as $foto) {
            IntervencionVistaIntervencion::query()->create([
                "intervencion_id" => $intervencion->id,
                "foto" => $foto,
            ]);
        }


        return $intervencion;
    }

    public function visualizar(int|string $id)
    {
        return Intervencion::query()
            ->with([
                "categoria", "denominacion", "directorEncargado", "conservadorResponsable",
                "procesos", "vistaGenerales", "vistaDetalles", "vistaIntervenciones",
                "creadoPor", "modificadoPor", "eliminadoPor"
            ])
            ->findOrFail($id);
    }

    public function modificar(int|string $id, array $data)
    {
        $data = collect($data);
        $intervencion = Intervencion::query()->findOrFail($id);

        $intervencion->fill($data->except("numero_ficha", "procesos", "vista_generales", "vista_detalles", "vista_intervenciones")->toArray());
        $intervencion->update();

        // Actualizar procesos
        IntervencionProceso::query()->where("intervencion_id", $intervencion->id)->delete();
        foreach ($data->get("procesos", []) ?? [] as $proceso) {
            IntervencionProceso::query()->create([
                "intervencion_id" => $intervencion->id,
                "area_material_intervenido" => $proceso['area_material_intervenido'] ?? null,
                "intervencion_tratamiento" => $proceso['intervencion_tratamiento'] ?? null,
                "insumo_producto_herramiental" => $proceso['insumo_producto_herramiental'] ?? null,
                "procedimiento" => $proceso['procedimiento'] ?? null,
            ]);
        }

        // Guardar vistas generales
        foreach ($data->get("vista_generales", []) ?? [] as $foto) {
            IntervencionVistaGeneral::query()->create([
                "intervencion_id" => $intervencion->id,
                "foto" => $foto,
            ]);
        }
        // Guardar vistas de detalle
        foreach ($data->get("vista_detalles", []) ?? [] as $foto) {
            IntervencionVistaDetalle::query()->create([
                "intervencion_id" => $intervencion->id,
                "foto" => $foto,
            ]);
        }

        // Guardar vistas de intervención
        foreach ($data->get("vista_intervenciones", []) ?? [] as $foto) {
            IntervencionVistaIntervencion::query()->create([
                "intervencion_id" => $intervencion->id,
                "foto" => $foto,
            ]);
        }

        return $intervencion;
    }

    public function eliminar(int|string $id, int|string $userId)
    {
        $intervencion = Intervencion::query()->findOrFail($id);

        // Eliminar todas las vistas generales
        $vistasGenerales = IntervencionVistaGeneral::query()->where("intervencion_id", $intervencion->id)->get();
        foreach ($vistasGenerales as $vista) {
            Storage::delete("dashboard/intervenciones/vista_generales/{$vista->foto}");
            $vista->delete();
        }

        // Eliminar todas las vistas de detalle
        $vistasDetalles = IntervencionVistaDetalle::query()->where("intervencion_id", $intervencion->id)->get();
        foreach ($vistasDetalles as $vista) {
            Storage::delete("dashboard/intervenciones/vista_detalles/{$vista->foto}");
            $vista->delete();
        }

        // Eliminar todas las vistas de intervención
        $vistasIntervenciones = IntervencionVistaIntervencion::query()->where("intervencion_id", $intervencion->id)->get();
        foreach ($vistasIntervenciones as $vista) {
            Storage::delete("dashboard/intervenciones/vista_intervenciones/{$vista->foto}");
            $vista->delete();
        }

        $intervencion->eliminado_por_usuario_id = $userId;
        $intervencion->update();
        $intervencion->delete();

        return $intervencion;
    }

    public function generarNumeroFicha()
    {
        $anio = now()->year;
        $cantidadRegistros = Intervencion::query()->whereYear("created_at", $anio)->count();
        $nuevoNumero = $cantidadRegistros + 1;
        return Str::padLeft($nuevoNumero, 8, "0") . "-" . $anio;
    }
}
