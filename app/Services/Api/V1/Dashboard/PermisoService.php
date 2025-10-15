<?php

namespace App\Services\Api\V1\Dashboard;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermisoService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return Permission::query()
            ->when(!empty($search),fn($q)=>$q->where(DB::raw("LOWER(name)"),"LIKE","%{$search}%"))
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));
    }

}
