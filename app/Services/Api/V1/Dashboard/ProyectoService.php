<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Proyecto;
use App\Models\ProyectoResponsable;
use App\Models\ProyectoTipoDocumento;
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
        $search = str($request->get("search"))->lower()->toString();
        $fechaDesde = $request->get("fecha_desde");
        $fechaHasta = $request->get("fecha_hasta");
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all", false), FILTER_VALIDATE_BOOLEAN);

        return Proyecto::query()
            ->with([
                "creadoPor", "modificadoPor", "eliminadoPor",
                "responsables",
                "tiposDocumento",
                "documentos", "fotografias"
            ])
            ->when(!empty($search), fn($q) => $q->where(function ($query) use ($search) {
                $query->where(DB::raw("LOWER(codigo_proyecto)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(nombre)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(descripcion)"), "LIKE", "%{$search}%");
            }))
            ->when(!empty($fechaDesde), fn($q) => $q->whereDate("fecha_inicio", ">=", $fechaDesde))
            ->when(!empty($fechaHasta), fn($q) => $q->whereDate("fecha_fin", "<=", $fechaHasta))
            ->orderBy("id", "DESC")
            ->when($listAll, fn($q) => $q->when(!empty($take) && $take > 0, fn($q) => $q->take($take))->get())
            ->when(!$listAll, fn($q) => $q->paginate($perPage, "*", "page", $currentPage));
    }

    public function guardar(array $data)
    {
        $data = collect($data);
        // Crear proyecto
        $proyecto = new Proyecto();
        $proyecto->fill($data->except("responsables", "tipos_documento", "documentos", "fotografias")->toArray());
        $proyecto->codigo_proyecto = $this->generarCodigoProyecto();
        $proyecto->save();

        // Guardar responsables del proyecto
        foreach ($data->get("responsables", []) ?? [] as $responsable) {
            ProyectoResponsable::query()->create([
                "proyecto_id" => $proyecto->id,
                "responsable_id" => $responsable['responsable_id'],
                "especialidad_id" => $responsable['especialidad_id'] ?? null,
            ]);
        }

        // Guardar tipos de documento
        foreach ($data->get("tipos_documento", []) ?? [] as $tipoDoc) {
            ProyectoTipoDocumento::query()->create([
                "proyecto_id" => $proyecto->id,
                "tipo_documento_id" => $tipoDoc['tipo_documento_id'],
                "dias_plazo" => $tipoDoc['dias_plazo'] ?? null,
                "penalidad" => $tipoDoc['penalidad'] ?? null,
            ]);
        }

        // Guardar documentos
        foreach ($data->get("documentos", []) ?? [] as $archivo) {
            ProyectoDocumento::query()->create([
                "proyecto_id" => $proyecto->id,
                "archivo" => $archivo,
            ]);
        }

        // Guardar fotografías
        foreach ($data->get("fotografias", []) ?? [] as $foto) {
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
                "responsables.responsable", "responsables.especialidad",
                "tiposDocumento.tipoDocumento",
                "documentos", "fotografias",
                "creadoPor", "modificadoPor", "eliminadoPor"
            ])
            ->findOrFail($id);
    }

    public function modificar(int|string $id, array $data)
    {
        $data = collect($data);
        $proyecto = Proyecto::query()->findOrFail($id);

        // Actualizar proyecto
        $proyecto->fill($data->except("codigo_proyecto", "responsables", "tipos_documento", "documentos", "fotografias")->toArray());
        $proyecto->update();

        // Actualizar responsables
        ProyectoResponsable::query()->where("proyecto_id", $proyecto->id)->delete();
        foreach ($data->get("responsables", []) ?? [] as $responsable) {
            ProyectoResponsable::query()->create([
                "proyecto_id" => $proyecto->id,
                "responsable_id" => $responsable['responsable_id'],
                "especialidad_id" => $responsable['especialidad_id'] ?? null,
            ]);
        }

        // Actualizar tipos de documento
        ProyectoTipoDocumento::query()->where("proyecto_id", $proyecto->id)->delete();
        foreach ($data->get("tipos_documento", []) ?? [] as $tipoDoc) {
            ProyectoTipoDocumento::query()->create([
                "proyecto_id" => $proyecto->id,
                "tipo_documento_id" => $tipoDoc['tipo_documento_id'],
                "dias_plazo" => $tipoDoc['dias_plazo'] ?? null,
                "penalidad" => $tipoDoc['penalidad'] ?? null,
            ]);
        }

        // Guardar nuevos documentos
        foreach ($data->get("documentos", []) ?? [] as $archivo) {
            ProyectoDocumento::query()->create([
                "proyecto_id" => $proyecto->id,
                "archivo" => $archivo,
            ]);
        }

        // Guardar nuevas fotografías
        foreach ($data->get("fotografias", []) ?? [] as $foto) {
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

        // Eliminar todas las fotografías
        $imagenes = ProyectoImagen::query()->where("proyecto_id", $proyecto->id)->get();
        foreach ($imagenes as $imagen) {
            Storage::delete("dashboard/proyectos/{$imagen->foto}");
            $imagen->delete();
        }

        // Eliminar responsables
        ProyectoResponsable::query()->where("proyecto_id", $proyecto->id)->delete();

        // Eliminar tipos de documento
        ProyectoTipoDocumento::query()->where("proyecto_id", $proyecto->id)->delete();

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

