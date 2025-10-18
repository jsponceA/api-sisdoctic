<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UsuarioService
{
    public function queryListado(array $params = [])
    {
        $request = collect($params);
        $search = str($request->get("search"))->lower()->toString();
        $perPage = $request->get("per_page", 15);
        $take = $request->get("take", 0);
        $currentPage = $request->get("current_page", 1);
        $listAll = filter_var($request->get("list_all",false),FILTER_VALIDATE_BOOLEAN);

        return User::query()
            ->with(["roles","tokens" => fn($q) => $q->latest("last_used_at")->limit(1)])
            ->when(!empty($search),fn($q)=>$q
                ->where(DB::raw("LOWER(username)"),"LIKE","%{$search}%")
                ->orWhere(DB::raw("LOWER(nombres)"),"LIKE","%{$search}%")
                ->orWhere(DB::raw("LOWER(apellidos)"),"LIKE","%{$search}%")
                ->orWhere(DB::raw("LOWER(correo)"),"LIKE","%{$search}%")
            )
            ->orderBy("id", "DESC")
            ->when($listAll,fn($q) => $q->when(!empty($take) && $take > 0,fn($q)=>$q->take($take))->get())
            ->when(!$listAll,fn($q) => $q->paginate($perPage, "*", "page", $currentPage));
    }

    public function guardar(array $data)
    {
        $data = collect($data);
        $usuario = new User();
        $usuario->fill($data->except("foto","rol")->toArray());
        if (!empty($data->get("foto"))){
         $usuario->foto = basename(Storage::putFile("dashboard/usuarios",$data->get("foto")));
        }
        if (!empty($data->get("password"))){
            $usuario->password = bcrypt($data->get("password"));
        }
        $usuario->save();
        $usuario->assignRole($data->get("rol"));

        return $usuario;
    }

    public function visualizar(int | string $id)
    {
        return User::query()->with(["roles" =>fn($q) => $q->with(["permissions"])])->findOrFail($id);
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $usuario = User::query()->findOrFail($id);
        $usuario->fill($data->except("foto","rol")->toArray());
        if (!empty($data->get("foto"))){
            if (Storage::exists("dashboard/usuarios/{$usuario->foto}")){
                Storage::delete("dashboard/usuarios/{$usuario->foto}");
            }
            $usuario->foto = basename(Storage::putFile("dashboard/usuarios",$data->get("foto")));
        }
        if (!empty($data->get("password"))){
            $usuario->password = bcrypt($data->get("password"));
        } else {
            unset($usuario->password);
        }
        $usuario->update();
        $usuario->syncRoles($data->get("rol"));
        return $usuario->load(["roles" =>fn($q) => $q->with(["permissions"])]);
    }

    public function eliminar(int | string $id, int | string $userId)
    {
        $usuario = User::query()->findOrFail($id);
        $usuario->tokens()->delete(); // Eliminar tokens asociados al usuario
        $usuario->eliminado_por_usuario_id = $userId; // Registrar quien elimina
        $usuario->update();
        return $usuario->delete();
    }

    public function habilitar(int | string $id, int | string $userId)
    {
        $usuario = User::query()->findOrFail($id);
        $usuario->modificado_por_usuario_id = $userId; // Registrar quien habilita
        $usuario->estado = 1; // Cambiar estado a habilitado
        return $usuario->update();
    }

    public function deshabilitar(int | string $id, int | string $userId)
    {
        $usuario = User::query()->findOrFail($id);
        $usuario->tokens()->delete(); // Eliminar tokens asociados al usuario
        $usuario->modificado_por_usuario_id = $userId; // Registrar quien deshabilita
        $usuario->estado = 0; // Cambiar estado a deshabilitado
        return $usuario->update();
    }
}
