<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Denominacion;
use Illuminate\Support\Facades\DB;

class DenominacionService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Denominacion::query()
            ->with(["creadoPor","modificadoPor","eliminadoPor"])
            ->when(!empty($search),fn($q)=>$q->where(DB::raw("LOWER(nombre)"),"LIKE","%{$search}%"))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $denominacion = new Denominacion();
        $denominacion->fill($data->toArray());
        $denominacion->save();
        return $denominacion;
    }

    public function visualizar(int | string $id)
    {
        return Denominacion::query()->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $denominacion = Denominacion::query()->findOrFail($id);
        $denominacion->fill($data->toArray());
        $denominacion->update();
        return $denominacion;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $denominacion = Denominacion::query()->findOrFail($id);
        $denominacion->eliminado_por_usuario_id = $userId;
        $denominacion->update();
        $denominacion->delete();
        return $denominacion;
    }
}

