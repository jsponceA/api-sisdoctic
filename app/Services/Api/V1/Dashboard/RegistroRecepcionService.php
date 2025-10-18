<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\RegistroRecepcion;
use App\Models\RegistroRecepcionDocumento;
use App\Models\RegistroRecepcionDocumentoRespuesta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistroRecepcionService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $proyectoId = $request->get("proyecto_id");
        $tipoDocumentoId = $request->get("tipo_documento_id");
        $especialidadId = $request->get("especialidad_id");
        $situacion = $request->get("situacion");
        $prioridad = $request->get("prioridad");
        $fechaDesde = $request->get("fecha_desde");
        $fechaHasta = $request->get("fecha_hasta");
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all", false), FILTER_VALIDATE_BOOLEAN);

        return RegistroRecepcion::query()
            ->with([
                "creadoPor", "modificadoPor", "eliminadoPor",
                "proyecto", "tipoDocumento", "tipoDocumentoClasificacion", "especialidad",
                "documentosAdjuntos", "documentosRespuesta"
            ])
            ->when(!empty($search), fn($q) => $q->where(function ($query) use ($search) {
                $query->where(DB::raw("LOWER(num_doc_recep)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(asunto)"), "LIKE", "%{$search}%")
                    ->orWhere(DB::raw("LOWER(destino)"), "LIKE", "%{$search}%");
            }))
            ->when(!empty($proyectoId), fn($q) => $q->where("proyecto_id", $proyectoId))
            ->when(!empty($tipoDocumentoId), fn($q) => $q->where("tipo_documento_id", $tipoDocumentoId))
            ->when(!empty($especialidadId), fn($q) => $q->where("especialidad_id", $especialidadId))
            ->when(!empty($situacion), fn($q) => $q->where("situacion", $situacion))
            ->when(!empty($prioridad), fn($q) => $q->where("prioridad", $prioridad))
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
        $registro->fill($data->except("documentos_adjuntos", "documentos_respuesta")->toArray());
        $registro->save();

        // Guardar documentos adjuntos
        foreach ($data->get("documentos_adjuntos", []) ?? [] as $archivo) {
            RegistroRecepcionDocumento::query()->create([
                "registro_recepcion_id" => $registro->id,
                "archivo" => $archivo,
                "nombre_original" => basename($archivo),
            ]);
        }

        // Guardar documentos de respuesta
        foreach ($data->get("documentos_respuesta", []) ?? [] as $archivo) {
            RegistroRecepcionDocumentoRespuesta::query()->create([
                "registro_recepcion_id" => $registro->id,
                "archivo" => $archivo,
                "nombre_original" => basename($archivo),
            ]);
        }

        return $registro;
    }

    public function visualizar(int|string $id)
    {
        return RegistroRecepcion::query()
            ->with([
                "proyecto", "tipoDocumento", "tipoDocumentoClasificacion", "especialidad",
                "documentosAdjuntos", "documentosRespuesta",
                "creadoPor", "modificadoPor", "eliminadoPor"
            ])
            ->findOrFail($id);
    }

    public function modificar(int|string $id, array $data)
    {
        $data = collect($data);
        $registro = RegistroRecepcion::query()->findOrFail($id);

        $registro->fill($data->except("documentos_adjuntos", "documentos_respuesta")->toArray());
        $registro->update();

        // Guardar nuevos documentos adjuntos
        foreach ($data->get("documentos_adjuntos", []) ?? [] as $archivo) {
            RegistroRecepcionDocumento::query()->create([
                "registro_recepcion_id" => $registro->id,
                "archivo" => $archivo,
                "nombre_original" => basename($archivo),
            ]);
        }

        // Guardar nuevos documentos de respuesta
        foreach ($data->get("documentos_respuesta", []) ?? [] as $archivo) {
            RegistroRecepcionDocumentoRespuesta::query()->create([
                "registro_recepcion_id" => $registro->id,
                "archivo" => $archivo,
                "nombre_original" => basename($archivo),
            ]);
        }
        return $registro;
    }

    public function eliminar(int|string $id, int|string $userId)
    {
        $registro = RegistroRecepcion::query()->findOrFail($id);

        // Eliminar todos los documentos adjuntos
        $documentos = RegistroRecepcionDocumento::query()->where("registro_recepcion_id", $registro->id)->get();
        foreach ($documentos as $documento) {
            if (!empty($documento->archivo)) {
                Storage::delete("dashboard/registro-recepciones/{$documento->archivo}");
            }
            $documento->delete();
        }

        // Eliminar todos los documentos de respuesta
        $documentosRespuesta = RegistroRecepcionDocumentoRespuesta::query()->where("registro_recepcion_id", $registro->id)->get();
        foreach ($documentosRespuesta as $documento) {
            if (!empty($documento->archivo)) {
                Storage::delete("dashboard/registro-recepciones/{$documento->archivo}");
            }
            $documento->delete();
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

    public function eliminarDocumentoRespuesta(int|string $documentoId)
    {
        $documento = RegistroRecepcionDocumentoRespuesta::query()->findOrFail($documentoId);
        if (!empty($documento->archivo)) {
            Storage::delete("dashboard/registro-recepciones/{$documento->archivo}");
        }
        $documento->delete();
        return true;
    }

    public function  modificarEstadoRespuesta(int|string $documentoId, string $estado)
    {
        $registro = RegistroRecepcion::query()->findOrFail($documentoId);
        $registro->estado_documento = $estado;
        $registro->update();
        return $registro;
    }
}

