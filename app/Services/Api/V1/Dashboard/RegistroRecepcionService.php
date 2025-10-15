<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\RegistroRecepcion;
use App\Models\RegistroRecepcionDocumento;
use App\Models\RegistroRecepcionImagen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistroRecepcionService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower();
        $proyectoId = $request->get("proyecto_id");
        $responsableId = $request->get("responsable_id");
        $tipoRecepcion = $request->get("tipo_recepcion");
        $estadoProceso = $request->get("estado_proceso");
        $fechaDesde = $request->get("fecha_desde");
        $fechaHasta = $request->get("fecha_hasta");
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all", false), FILTER_VALIDATE_BOOLEAN);

        return RegistroRecepcion::query()
            ->with([
                "creadoPor", "modificadoPor", "eliminadoPor",
                "proyecto", "responsable", "tipoMaterial",
                "documentos", "imagenes"
            ])
            ->when(!empty($search), fn($q) => $q->where(function ($query) use ($search) {
                $query->where(DB::raw("LOWER(numero_recepcion)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(remitente)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(procedencia)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(descripcion_material)"), "LIKE", "%{$search}%");
            }))
            ->when(!empty($proyectoId), fn($q) => $q->where("proyecto_id", $proyectoId))
            ->when(!empty($responsableId), fn($q) => $q->where("responsable_id", $responsableId))
            ->when(!empty($tipoRecepcion), fn($q) => $q->where("tipo_recepcion", $tipoRecepcion))
            ->when(!empty($estadoProceso), fn($q) => $q->where("estado_proceso", $estadoProceso))
            ->when(!empty($fechaDesde), fn($q) => $q->whereDate("fecha_recepcion", ">=", $fechaDesde))
            ->when(!empty($fechaHasta), fn($q) => $q->whereDate("fecha_recepcion", "<=", $fechaHasta))
            ->orderBy("id", "DESC")
            ->when($listAll, fn($q) => $q->when(!empty($take) && $take > 0, fn($q) => $q->take($take))->get())
            ->when(!$listAll, fn($q) => $q->paginate($perPage, "*", "page", $currentPage));
    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $registro = new RegistroRecepcion();
        $registro->fill($data->except("documentos", "imagenes")->toArray());
        $registro->numero_recepcion = $this->generarNumeroRecepcion();
        $registro->save();

        // Guardar documentos
        foreach ($data->get("documentos", []) ?? [] as $documento) {
            RegistroRecepcionDocumento::query()->create([
                "registro_recepcion_id" => $registro->id,
                "archivo" => $documento['archivo'] ?? null,
                "nombre_documento" => $documento['nombre_documento'] ?? null,
                "tipo_documento" => $documento['tipo_documento'] ?? null,
            ]);
        }

        // Guardar imágenes
        foreach ($data->get("imagenes", []) ?? [] as $imagen) {
            RegistroRecepcionImagen::query()->create([
                "registro_recepcion_id" => $registro->id,
                "foto" => $imagen['foto'] ?? null,
                "descripcion_foto" => $imagen['descripcion_foto'] ?? null,
                "tipo_foto" => $imagen['tipo_foto'] ?? null,
            ]);
        }

        return $registro;
    }

    public function visualizar(int|string $id)
    {
        return RegistroRecepcion::query()
            ->with([
                "proyecto", "responsable", "tipoMaterial",
                "documentos", "imagenes",
                "creadoPor", "modificadoPor", "eliminadoPor"
            ])
            ->findOrFail($id);
    }

    public function modificar(int|string $id, array $data)
    {
        $data = collect($data);
        $registro = RegistroRecepcion::query()->findOrFail($id);

        $registro->fill($data->except("numero_recepcion", "documentos", "imagenes")->toArray());
        $registro->update();

        // Guardar nuevos documentos (no eliminamos los anteriores, solo agregamos)
        foreach ($data->get("documentos", []) ?? [] as $documento) {
            if (!empty($documento['archivo'])) {
                RegistroRecepcionDocumento::query()->create([
                    "registro_recepcion_id" => $registro->id,
                    "archivo" => $documento['archivo'],
                    "nombre_documento" => $documento['nombre_documento'] ?? null,
                    "tipo_documento" => $documento['tipo_documento'] ?? null,
                ]);
            }
        }

        // Guardar nuevas imágenes
        foreach ($data->get("imagenes", []) ?? [] as $imagen) {
            if (!empty($imagen['foto'])) {
                RegistroRecepcionImagen::query()->create([
                    "registro_recepcion_id" => $registro->id,
                    "foto" => $imagen['foto'],
                    "descripcion_foto" => $imagen['descripcion_foto'] ?? null,
                    "tipo_foto" => $imagen['tipo_foto'] ?? null,
                ]);
            }
        }

        return $registro;
    }

    public function eliminar(int|string $id, int|string $userId)
    {
        $registro = RegistroRecepcion::query()->findOrFail($id);

        // Eliminar todos los documentos
        $documentos = RegistroRecepcionDocumento::query()->where("registro_recepcion_id", $registro->id)->get();
        foreach ($documentos as $documento) {
            if (!empty($documento->archivo)) {
                Storage::delete("dashboard/registro-recepciones/{$documento->archivo}");
            }
            $documento->delete();
        }

        // Eliminar todas las imágenes
        $imagenes = RegistroRecepcionImagen::query()->where("registro_recepcion_id", $registro->id)->get();
        foreach ($imagenes as $imagen) {
            if (!empty($imagen->foto)) {
                Storage::delete("dashboard/registro-recepciones/{$imagen->foto}");
            }
            $imagen->delete();
        }

        $registro->eliminado_por_usuario_id = $userId;
        $registro->update();
        $registro->delete();

        return $registro;
    }

    public function generarCodigoRecepcion()
    {
        $anio = now()->year;
        $cantidadRegistros = RegistroRecepcion::query()->whereYear("created_at", $anio)->count();
        $nuevoNumero = $cantidadRegistros + 1;
        return "REC-" . Str::padLeft($nuevoNumero, 6, "0") . "-" . $anio;
    }

    public function eliminarDocumento(int|string $documentoId)
    {
        $documento = RegistroRecepcionDocumento::query()->findOrFail($documentoId);
        if (!empty($documento->archivo)) {
            Storage::delete("dashboard/registro-recepciones/{$documento->archivo}");
        }
        $documento->delete();
        return true;
    }

    public function eliminarImagen(int|string $imagenId)
    {
        $imagen = RegistroRecepcionImagen::query()->findOrFail($imagenId);
        if (!empty($imagen->foto)) {
            Storage::delete("dashboard/registro-recepciones/{$imagen->foto}");
        }
        $imagen->delete();
        return true;
    }
}

