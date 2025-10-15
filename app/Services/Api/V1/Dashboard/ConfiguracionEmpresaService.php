<?php

namespace App\Services\Api\V1\Dashboard;

use App\Models\ConfiguracionEmpresa;
use Illuminate\Support\Facades\Storage;

class ConfiguracionEmpresaService
{
    public function visualizar(int | string $id = null)
    {
        return !empty($id) && !is_string($id) ? ConfiguracionEmpresa::query()->findOrFail($id) : ConfiguracionEmpresa::query()->latest()->first();
    }

    public function modificar(int | string $id,array $data)
    {
        $data = collect($data);
        $configuracionEmpresa = ConfiguracionEmpresa::query()->findOrFail($id);
        $configuracionEmpresa->fill($data->except("logo","favicon")->toArray());

        if (!empty($data->get("logo"))){
            if (Storage::exists("dashboard/configuracion-empresa/{$configuracionEmpresa->logo}")){
                Storage::delete("dashboard/configuracion-empresa/{$configuracionEmpresa->logo}");
            }
            $configuracionEmpresa->logo = basename(Storage::putFile("dashboard/configuracion-empresa",$data->get("logo")));
        }
        if (!empty($data->get("favicon"))){
            if (Storage::exists("dashboard/configuracion-empresa/{$configuracionEmpresa->favicon}")){
                Storage::delete("dashboard/configuracion-empresa/{$configuracionEmpresa->favicon}");
            }
            $configuracionEmpresa->favicon = basename(Storage::putFile("dashboard/configuracion-empresa",$data->get("favicon")));
        }
        $configuracionEmpresa->update();
        return $configuracionEmpresa;
    }
}
