<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Http;

class ConsultaDocumentoService
{
    CONST DECOLECTA_URL = "https://api.decolecta.com/v1";
    CONST TOKEN = "sk_9182.T8ia0oYm17KyZi32EVlTarw1lvf3PJ7R";

    public function buscarDni(string $dni){
        $request = Http::withoutVerifying()->withToken(self::TOKEN)->get(self::DECOLECTA_URL."/reniec/dni",[
            "numero" => $dni
        ]);
        if ($request->ok()) {
            $data = $request->object();
            return [
                "status" => $request->getStatusCode(),
                "nombres" => $data->first_name,
                "apellidos" => $data->first_last_name." ".$data->second_last_name,
            ];
        }else{
            return [
                "status" => $request->getStatusCode(),
                "message" =>  "DNI no encotrado, vuelva a intentarlo.",
            ];
        }
    }

    public function buscarRuc(string $ruc)
    {
        $request = Http::withoutVerifying()->withToken(self::TOKEN)->get(self::DECOLECTA_URL."/sunat/ruc",[
            "numero" => $ruc
        ]);
        if ($request->ok()) {
            $data = $request->object();
            return [
                "status" => $request->getStatusCode(),
                "razon_social" => $data->razon_social,
                "direccion" => $data->direccion,
            ];
        }else{
            return [
                "status" => $request->getStatusCode(),
                "message" => "RUC no encotrado, vuelva a intentarlo.",
            ];
        }
    }

}
