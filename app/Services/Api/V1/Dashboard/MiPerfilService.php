<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class MiPerfilService
{

    public function visualizar(int | string $id)
    {
        return User::query()->with(["roles"])->findOrFail($id);
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
        return $usuario->load(["roles" =>fn($q) => $q->with(["permissions"])]);
    }

    public function sesionesAbiertas(int | string $id, int | string $currentTokenId)
    {
        $usuario = User::query()->findOrFail($id);
        $sessiones = PersonalAccessToken::query()
            ->whereNot("id",$currentTokenId)
            ->select("id","name","last_used_at","created_at")
            ->where("tokenable_id",$usuario->id)
            ->orderBy("last_used_at","DESC")
            ->get();

        return $sessiones;
    }

    public function eliminarSesion(int | string $id, int | string $tokenId, int | string $currentTokenId)
    {
        $usuario = User::query()->findOrFail($id);

        $sesion = PersonalAccessToken::query()
            ->whereNot("id",$currentTokenId)
            ->where("id",$tokenId)
            ->where("tokenable_id",$usuario->id)
            ->delete();
    }

    public function eliminarTodasSesiones(int | string $id, int | string $currentTokenId)
    {
        $usuario = User::query()->findOrFail($id);
        $sesiones = PersonalAccessToken::query()
            ->whereNot("id",$currentTokenId)
            ->where("tokenable_id",$usuario->id)
            ->delete();
    }


}
