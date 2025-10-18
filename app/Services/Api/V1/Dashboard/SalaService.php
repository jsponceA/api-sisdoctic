<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Sala;
use Illuminate\Support\Facades\DB;

class SalaService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Sala::query()
            ->with(["creadoPor","modificadoPor","eliminadoPor"])
            ->when(!empty($search),fn($q)=>$q->where(DB::raw("LOWER(nombre)"),"LIKE","%{$search}%"))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $sala = new Sala();
        $sala->fill($data->toArray());
        $sala->save();
        return $sala;
    }

    public function visualizar(int | string $id)
    {
        return Sala::query()->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $sala = Sala::query()->findOrFail($id);
        $sala->fill($data->toArray());
        $sala->update();
        return $sala;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $sala = Sala::query()->findOrFail($id);
        $sala->eliminado_por_usuario_id = $userId;
        $sala->update();
        $sala->delete();
        return $sala;
    }
}
