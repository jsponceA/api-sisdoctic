<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Area;
use Illuminate\Support\Facades\DB;

class AreaService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Area::query()
            ->with(["creadoPor","modificadoPor","eliminadoPor"])
            ->when(!empty($search),fn($q)=>$q->where(function($q) use ($search){
                $q->where(DB::raw("LOWER(nombre)"),"LIKE","%{$search}%")
                  ->orWhere(DB::raw("LOWER(descripcion)"),"LIKE","%{$search}%");
            }))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $area = new Area();
        $area->fill($data->toArray());
        $area->save();
        return $area;
    }

    public function visualizar(int | string $id)
    {
        return Area::query()->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $area = Area::query()->findOrFail($id);
        $area->fill($data->toArray());
        $area->update();
        return $area;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $area = Area::query()->findOrFail($id);
        $area->eliminado_por_usuario_id = $userId;
        $area->update();
        $area->delete();
        return $area;
    }
}

