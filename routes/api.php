<?php


use App\Http\Controllers\Api\V1\ConfiguracionEmpresaController;
use App\Http\Controllers\Api\V1\ConsultaDocumentoController;
use App\Http\Controllers\Api\V1\Web\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    $ip = request()->ip();
    return response()->json("SERVICIO PRIVADO SU IP: {$ip}  QUEDARA REGISTRADA COMO PROCESO DE SEGURIDAD, SI NO ESTA AUTORIZADO AQUI NO DEBERIA ESTAR");
});

Route::group(["prefix" => "v1","as" => "api.v1."], function () {
    Route::get("health", function () {
        return response()->json([
            "status" => "ok",
            "message" => "Servicio de API en funcionamiento"
        ]);
    });

    /* RUTAS PARA CONSULTAR DOCUMENTOS EXTERNOS DE OTRAS APIS EXTERNAS */
    Route::get("consulta-documento/dni/{dni}", [ConsultaDocumentoController::class, "buscarDni"]);
    Route::get("consulta-documento/ruc/{ruc}", [ConsultaDocumentoController::class, "buscarRuc"]);

    /* RUTA PARA TRAER LA CONFIGURACION DE LA EMPRESA COMO NOMBRE, LOGO, FAVICON Y DATOS DEL SEO ENTRE OTROS MAS */
    Route::get("configuracion-empresa/mostrar-informacion-general", [ConfiguracionEmpresaController::class, "mostrarInformacionGeneral"]);


    //  Route::apiResource("usuarios",UsuarioController::class);
});
