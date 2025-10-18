<?php

namespace App\Services\Api\V1\Dashboard;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Role::query()
            ->withCount(["permissions"])
            ->when(!empty($search),fn($q)=>$q->where(DB::raw("LOWER(name)"),"LIKE","%{$search}%"))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));

    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $rol = new Role();
        $rol->fill($data->except("permissions")->toArray());
        $rol->guard_name = "web";
        $rol->save();
        $rol->givePermissionTo($data->get("permissions"));
        return $rol;
    }

    public function visualizar(int | string $id)
    {
        return Role::query()->with(["permissions"])->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $rol = Role::query()->findOrFail($id);
        $rol->update($data->except("permissions")->toArray());
        $rol->syncPermissions($data->get("permissions"));
        return $rol;
    }

    public function eliminar(int | string $id)
    {
       return Role::query()->findOrFail($id)->delete();
    }

}
