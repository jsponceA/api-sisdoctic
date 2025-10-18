<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\Especialidad;
use Illuminate\Support\Facades\DB;

class EspecialidadService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Especialidad::query()
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
        $especialidad = new Especialidad();
        $especialidad->fill($data->toArray());
        $especialidad->save();
        return $especialidad;
    }

    public function visualizar(int | string $id)
    {
        return Especialidad::query()->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $especialidad = Especialidad::query()->findOrFail($id);
        $especialidad->fill($data->toArray());
        $especialidad->update();
        return $especialidad;
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $especialidad = Especialidad::query()->findOrFail($id);
        $especialidad->eliminado_por_usuario_id = $userId;
        $especialidad->update();
        $especialidad->delete();
        return $especialidad;
    }
}

