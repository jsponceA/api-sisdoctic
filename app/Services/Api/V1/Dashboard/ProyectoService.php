<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Proyecto;
use App\Models\ProyectoActividad;
use App\Models\ProyectoDocumento;
use App\Models\ProyectoImagen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProyectoService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower();
        $categoriaId = $request->get("categoria_id");
        $responsableId = $request->get("responsable_id");
        $estado = $request->get("estado");
        $fechaDesde = $request->get("fecha_desde");
        $fechaHasta = $request->get("fecha_hasta");
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all", false), FILTER_VALIDATE_BOOLEAN);

        return Proyecto::query()
            ->with([
                "creadoPor", "modificadoPor", "eliminadoPor",
                "categoria", "responsable",
                "actividades", "documentos", "imagenes"
            ])
            ->when(!empty($search), fn($q) => $q->where(function ($query) use ($search) {
                $query->where(DB::raw("LOWER(codigo_proyecto)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(nombre_proyecto)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(ubicacion)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(descripcion)"), "LIKE", "%{$search}%");
            }))
            ->when(!empty($categoriaId), fn($q) => $q->where("categoria_id", $categoriaId))
            ->when(!empty($responsableId), fn($q) => $q->where("responsable_id", $responsableId))
            ->when(!empty($estado), fn($q) => $q->where("estado", $estado))
            ->when(!empty($fechaDesde), fn($q) => $q->whereDate("fecha_inicio", ">=", $fechaDesde))
            ->when(!empty($fechaHasta), fn($q) => $q->whereDate("fecha_fin", "<=", $fechaHasta))
            ->orderBy("id", "DESC")
            ->when($listAll, fn($q) => $q->when(!empty($take) && $take > 0, fn($q) => $q->take($take))->get())
            ->when(!$listAll, fn($q) => $q->paginate($perPage, "*", "page", $currentPage));
    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $proyecto = new Proyecto();
        $proyecto->fill($data->except("actividades", "documentos", "imagenes")->toArray());
        $proyecto->codigo_proyecto = $this->generarCodigoProyecto();
        $proyecto->save();

        // Guardar actividades del proyecto
        foreach ($data->get("actividades", []) ?? [] as $actividad) {
            ProyectoActividad::query()->create([
                "proyecto_id" => $proyecto->id,
                "nombre_actividad" => $actividad['nombre_actividad'] ?? null,
                "descripcion_actividad" => $actividad['descripcion_actividad'] ?? null,
                "fecha_programada" => $actividad['fecha_programada'] ?? null,
                "responsable_actividad" => $actividad['responsable_actividad'] ?? null,
                "estado_actividad" => $actividad['estado_actividad'] ?? null,
            ]);
        }

        // Guardar documentos
        foreach ($data->get("documentos", []) ?? [] as $archivo) {
            ProyectoDocumento::query()->create([
                "proyecto_id" => $proyecto->id,
                "archivo" => $archivo,
            ]);
        }

        // Guardar imágenes
        foreach ($data->get("imagenes", []) ?? [] as $foto) {
            ProyectoImagen::query()->create([
                "proyecto_id" => $proyecto->id,
                "foto" => $foto,
            ]);
        }

        return $proyecto;
    }

    public function visualizar(int|string $id)
    {
        return Proyecto::query()
            ->with([
                "categoria", "responsable",
                "actividades", "documentos", "imagenes",
                "creadoPor", "modificadoPor", "eliminadoPor"
            ])
            ->findOrFail($id);
    }

    public function modificar(int|string $id, array $data)
    {
        $data = collect($data);
        $proyecto = Proyecto::query()->findOrFail($id);

        $proyecto->fill($data->except("codigo_proyecto", "actividades", "documentos", "imagenes")->toArray());
        $proyecto->update();

        // Actualizar actividades
        ProyectoActividad::query()->where("proyecto_id", $proyecto->id)->delete();
        foreach ($data->get("actividades", []) ?? [] as $actividad) {
            ProyectoActividad::query()->create([
                "proyecto_id" => $proyecto->id,
                "nombre_actividad" => $actividad['nombre_actividad'] ?? null,
                "descripcion_actividad" => $actividad['descripcion_actividad'] ?? null,
                "fecha_programada" => $actividad['fecha_programada'] ?? null,
                "responsable_actividad" => $actividad['responsable_actividad'] ?? null,
                "estado_actividad" => $actividad['estado_actividad'] ?? null,
            ]);
        }

        // Guardar documentos
        foreach ($data->get("documentos", []) ?? [] as $archivo) {
            ProyectoDocumento::query()->create([
                "proyecto_id" => $proyecto->id,
                "archivo" => $archivo,
            ]);
        }

        // Guardar imágenes
        foreach ($data->get("imagenes", []) ?? [] as $foto) {
            ProyectoImagen::query()->create([
                "proyecto_id" => $proyecto->id,
                "foto" => $foto,
            ]);
        }

        return $proyecto;
    }

    public function eliminar(int|string $id, int|string $userId)
    {
        $proyecto = Proyecto::query()->findOrFail($id);

        // Eliminar todos los documentos
        $documentos = ProyectoDocumento::query()->where("proyecto_id", $proyecto->id)->get();
        foreach ($documentos as $documento) {
            Storage::delete("dashboard/proyectos/{$documento->archivo}");
            $documento->delete();
        }

        // Eliminar todas las imágenes
        $imagenes = ProyectoImagen::query()->where("proyecto_id", $proyecto->id)->get();
        foreach ($imagenes as $imagen) {
            Storage::delete("dashboard/proyectos/{$imagen->foto}");
            $imagen->delete();
        }

        // Eliminar actividades
        ProyectoActividad::query()->where("proyecto_id", $proyecto->id)->delete();

        $proyecto->eliminado_por_usuario_id = $userId;
        $proyecto->update();
        $proyecto->delete();

        return $proyecto;
    }

    public function generarCodigoProyecto()
    {
        $anio = now()->year;
        $cantidadRegistros = Proyecto::query()->whereYear("created_at", $anio)->count();
        $nuevoNumero = $cantidadRegistros + 1;
        return "PROY-" . Str::padLeft($nuevoNumero, 6, "0") . "-" . $anio;
    }
}

