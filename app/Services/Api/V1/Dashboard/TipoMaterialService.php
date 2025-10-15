<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\TipoMaterial;
use Illuminate\Support\Facades\DB;

class TipoMaterialService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return TipoMaterial::query()
            ->with(["creadoPor","modificadoPor","eliminadoPor"])
            ->when(!empty($search),fn($q)=>$q->where(DB::raw("LOWER(nombre)"),"LIKE","%{$search}%"))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $tipoMaterial = new TipoMaterial();
        $tipoMaterial->fill($data->toArray());
        $tipoMaterial->save();
        return $tipoMaterial;
    }

    public function visualizar(int | string $id)
    {
        return TipoMaterial::query()->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $tipoMaterial = TipoMaterial::query()->findOrFail($id);
        $tipoMaterial->fill($data->toArray());
        $tipoMaterial->update();
        return $tipoMaterial;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $tipoMaterial = TipoMaterial::query()->findOrFail($id);
        $tipoMaterial->eliminado_por_usuario_id = $userId;
        $tipoMaterial->update();
        $tipoMaterial->delete();
        return $tipoMaterial;
    }
}
