<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Responsable;
use Illuminate\Support\Facades\DB;

class ResponsableService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Responsable::query()
            ->with(["creadoPor","modificadoPor","eliminadoPor"])
            ->when(!empty($search),fn($q)=>$q->where(function($query) use ($search) {
                $query->where(DB::raw("LOWER(nombres)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(apellidos)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(correo)"),"LIKE","%{$search}%")
                      ->orWhere(DB::raw("LOWER(numero_documento)"),"LIKE","%{$search}%");
            }))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $responsable = new Responsable();
        $responsable->fill($data->toArray());
        $responsable->save();
        return $responsable;
    }

    public function visualizar(int | string $id)
    {
        return Responsable::query()->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $responsable = Responsable::query()->findOrFail($id);
        $responsable->fill($data->toArray());
        $responsable->update();
        return $responsable;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $responsable = Responsable::query()->findOrFail($id);
        $responsable->eliminado_por_usuario_id = $userId;
        $responsable->update();
        $responsable->delete();
        return $responsable;
    }
}
