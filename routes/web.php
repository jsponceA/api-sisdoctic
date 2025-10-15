<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $ip = request()->ip();
    return response()->json("SERVICIO PRIVADO SU IP: {$ip}  QUEDARA REGISTRADA COMO PROCESO DE SEGURIDAD, SI NO ESTA AUTORIZADO AQUI NO DEBERIA ESTAR");
});
