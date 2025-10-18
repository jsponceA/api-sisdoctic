<?php

use App\Http\Controllers\Api\V1\Dashboard\AreaController;
use App\Http\Controllers\Api\V1\Dashboard\ArticuloController;
use App\Http\Controllers\Api\V1\Dashboard\AuthController;
use App\Http\Controllers\Api\V1\Dashboard\CategoriaController;
use App\Http\Controllers\Api\V1\Dashboard\ConfiguracionEmpresaController;
use App\Http\Controllers\Api\V1\Dashboard\DenominacionController;
use App\Http\Controllers\Api\V1\Dashboard\EspecialidadController;
use App\Http\Controllers\Api\V1\Dashboard\EstadisticaController;
use App\Http\Controllers\Api\V1\Dashboard\EstadoConservacionController;
use App\Http\Controllers\Api\V1\Dashboard\IntervencionController;
use App\Http\Controllers\Api\V1\Dashboard\MiPerfilController;
use App\Http\Controllers\Api\V1\Dashboard\NotificacionController;
use App\Http\Controllers\Api\V1\Dashboard\PermisoController;
use App\Http\Controllers\Api\V1\Dashboard\ProyectoController;
use App\Http\Controllers\Api\V1\Dashboard\RegistroRecepcionController;
use App\Http\Controllers\Api\V1\Dashboard\ResponsableController;
use App\Http\Controllers\Api\V1\Dashboard\RolController;
use App\Http\Controllers\Api\V1\Dashboard\SalaController;
use App\Http\Controllers\Api\V1\Dashboard\TipoDocumentoClasificacionController;
use App\Http\Controllers\Api\V1\Dashboard\TipoDocumentoController;
use App\Http\Controllers\Api\V1\Dashboard\TipoMaterialController;
use App\Http\Controllers\Api\V1\Dashboard\UsuarioController;
use Illuminate\Support\Facades\Route;


Route::group(["prefix" => "v1/dashboard","as" => "api.v1.dashboard."], function () {
    /* RUTAS PARA EL LOGIN PARA EL DASHBOARD*/
    Route::post("auth/login", [AuthController::class, "login"]);

    /*RUTAS PROTEGIAS POR MIDDLEWARE SANCTUM SIEMPRE PEDIRA TOKEN EN CADA PETICION A ESTAS RUTAS O END POINTS*/
    Route::group(["middleware" => "auth:sanctum"], function () {
        /* RUTAS PARA EL LOGOUT,VERIFICAR TOKEN PARA EL DASHBOARD*/
        Route::post("auth/logout", [AuthController::class, "logout"]);
        Route::post("auth/current-session-user", [AuthController::class, "currentSessionUser"]);

        /* RUTAS PARA EL PERFIL LOGEADO*/
        Route::get("mi-perfil/sesiones-abiertas/{id}", [MiPerfilController::class, "sesionesAbiertas"]);
        Route::delete("mi-perfil/eliminar-sesion/{id}/{tokenId}", [MiPerfilController::class, "eliminarSesion"]);
        Route::delete("mi-perfil/eliminar-todas-sesiones/{id}", [MiPerfilController::class, "eliminarTodasSesiones"]);
        Route::apiResource("mi-perfil", MiPerfilController::class)->only(["show","update"]);

        /* RUTAS PARA LAS NOTIFICACIONES DEL PERFIL LOGEADO*/
        Route::apiResource("notificaciones", NotificacionController::class)->only(["index","update"]);

        /* RUTAS PARA LA CONFIGURACION EMPRESA*/
        Route::apiResource("configuracion-empresa", ConfiguracionEmpresaController::class)->only(["show","update"]);

        /* RUTAS MODULO PERMISOS*/
        Route::apiResource("permisos", PermisoController::class);

        /* RUTAS MODULO ROLES*/
        Route::post("roles/reporte-pdf", [RolController::class,"reportePdf"]);
        Route::post("roles/reporte-excel", [RolController::class,"reporteExcel"]);
        Route::apiResource("roles", RolController::class);

        /* RUTAS MODULO USUARIOS*/
        Route::patch("usuarios/habilitar/{id}", [UsuarioController::class,"habilitar"]);
        Route::patch("usuarios/deshabilitar/{id}", [UsuarioController::class,"deshabilitar"]);
        Route::post("usuarios/reporte-pdf", [UsuarioController::class,"reportePdf"]);
        Route::post("usuarios/reporte-excel", [UsuarioController::class,"reporteExcel"]);
        Route::apiResource("usuarios", UsuarioController::class);

        /* RUTAS MODULO TIPO MATERIAL*/
        Route::post("tipo-materiales/reporte-pdf", [TipoMaterialController::class,"reportePdf"]);
        Route::post("tipo-materiales/reporte-excel", [TipoMaterialController::class,"reporteExcel"]);
        Route::apiResource("tipo-materiales", TipoMaterialController::class);

        /* RUTAS MODULO DENOMINACIONES*/
        Route::post("denominaciones/reporte-pdf", [DenominacionController::class,"reportePdf"]);
        Route::post("denominaciones/reporte-excel", [DenominacionController::class,"reporteExcel"]);
        Route::apiResource("denominaciones", DenominacionController::class);

        /* RUTAS MODULO CATEGORIAS*/
        Route::post("categorias/reporte-pdf", [CategoriaController::class,"reportePdf"]);
        Route::post("categorias/reporte-excel", [CategoriaController::class,"reporteExcel"]);
        Route::apiResource("categorias", CategoriaController::class);

        /* RUTAS MODULO ESTADO CONSERVACION*/
        Route::post("estados-conservacion/reporte-pdf", [EstadoConservacionController::class,"reportePdf"]);
        Route::post("estados-conservacion/reporte-excel", [EstadoConservacionController::class,"reporteExcel"]);
        Route::apiResource("estados-conservacion", EstadoConservacionController::class);

        Route::post("responsables/reporte-pdf", [ResponsableController::class,"reportePdf"]);
        Route::post("responsables/reporte-excel", [ResponsableController::class,"reporteExcel"]);
        Route::apiResource("responsables", ResponsableController::class);

        /* RUTAS MODULO SALAS*/
        Route::post("salas/reporte-pdf", [SalaController::class,"reportePdf"]);
        Route::post("salas/reporte-excel", [SalaController::class,"reporteExcel"]);
        Route::apiResource("salas", SalaController::class);

        /* RUTAS MODULO ARTICULOS*/
        Route::get("articulos/generar-numero-ficha", [ArticuloController::class,"generarNumeroFicha"]);
        Route::post("articulos/ficha-conservacion-pdf/{id}", [ArticuloController::class,"fichaConservacionPdf"]);
        Route::post("articulos/reporte-pdf", [ArticuloController::class,"reportePdf"]);
        Route::post("articulos/reporte-excel", [ArticuloController::class,"reporteExcel"]);
        Route::apiResource("articulos", ArticuloController::class);

        /* RUTAS MODULO INTERVENCIONES*/
        Route::get("intervenciones/generar-numero-ficha", [IntervencionController::class,"generarNumeroFicha"]);
        Route::post("intervenciones/ficha-intervencion-pdf/{id}", [IntervencionController::class,"fichaIntervencionPdf"]);
        Route::post("intervenciones/reporte-pdf", [IntervencionController::class,"reportePdf"]);
        Route::post("intervenciones/reporte-excel", [IntervencionController::class,"reporteExcel"]);
        Route::apiResource("intervenciones", IntervencionController::class);

        /* RUTAS MODULO ESTADÍSTICAS - DASHBOARD*/
        Route::prefix("estadisticas")->group(function () {
            // Cards y resumen general
            Route::get("resumen-general", [EstadisticaController::class, "resumenGeneral"]);
            Route::get("dashboard-completo", [EstadisticaController::class, "dashboardCompleto"]);

            // Gráficos de Artículos
            Route::get("articulos/por-tipo-material", [EstadisticaController::class, "articulosPorTipoMaterial"]);
            Route::get("articulos/por-estado-conservacion", [EstadisticaController::class, "articulosPorEstadoConservacion"]);
            Route::get("articulos/por-sala", [EstadisticaController::class, "articulosPorSala"]);
            Route::get("articulos/por-denominacion", [EstadisticaController::class, "articulosPorDenominacion"]);
            Route::get("articulos/por-mes", [EstadisticaController::class, "articulosPorMes"]);
            Route::get("articulos/distribucion-integridad", [EstadisticaController::class, "distribucionIntegridad"]);

            // Gráficos de Intervenciones
            Route::get("intervenciones/por-categoria", [EstadisticaController::class, "intervencionesPorCategoria"]);
            Route::get("intervenciones/por-conservador", [EstadisticaController::class, "intervencionesPorConservador"]);
            Route::get("intervenciones/por-mes", [EstadisticaController::class, "intervencionesPorMes"]);
            Route::get("intervenciones/estado", [EstadisticaController::class, "estadoIntervenciones"]);

            // Tablas y rankings
            Route::get("responsables/top-articulos", [EstadisticaController::class, "topResponsablesArticulos"]);
            Route::get("ultimos-articulos", [EstadisticaController::class, "ultimosArticulos"]);
            Route::get("ultimas-intervenciones", [EstadisticaController::class, "ultimasIntervenciones"]);

            // Comparativas
            Route::get("comparativa-anual", [EstadisticaController::class, "comparativaAnual"]);
        });

        /* RUTAS MODULO PROYECTOS*/
        Route::get("proyectos/generar-codigo-proyecto", [ProyectoController::class,"generarCodigoProyecto"]);
        Route::post("proyectos/ficha-proyecto-pdf/{id}", [ProyectoController::class,"fichaProyectoPdf"]);
        Route::post("proyectos/reporte-pdf", [ProyectoController::class,"reportePdf"]);
        Route::post("proyectos/reporte-excel", [ProyectoController::class,"reporteExcel"]);
        Route::apiResource("proyectos", ProyectoController::class);

        /* RUTAS MODULO REGISTRO DE RECEPCIONES*/
        Route::get("registro-recepciones/generar-codigo-recepcion", [RegistroRecepcionController::class,"generarCodigoRecepcion"]);
        Route::patch("registro-recepciones/modificar-estado-respuesta/{id}", [RegistroRecepcionController::class,"modificarEstadoRespuesta"]);
        Route::post("registro-recepciones/ficha-recepcion-pdf/{id}", [RegistroRecepcionController::class,"fichaRecepcionPdf"]);
        Route::post("registro-recepciones/reporte-pdf", [RegistroRecepcionController::class,"reportePdf"]);
        Route::post("registro-recepciones/reporte-excel", [RegistroRecepcionController::class,"reporteExcel"]);
        Route::delete("registro-recepciones/eliminar-documento/{documentoId}", [RegistroRecepcionController::class,"eliminarDocumento"]);
        Route::delete("registro-recepciones/eliminar-documento-respuesta/{documentoId}", [RegistroRecepcionController::class,"eliminarDocumentoRespuesta"]);
        Route::apiResource("registro-recepciones", RegistroRecepcionController::class);

        /* RUTAS MODULO AREAS*/
        Route::post("areas/reporte-pdf", [AreaController::class,"reportePdf"]);
        Route::post("areas/reporte-excel", [AreaController::class,"reporteExcel"]);
        Route::apiResource("areas", AreaController::class);

        /* RUTAS MODULO ESPECIALIDADES*/
        Route::post("especialidades/reporte-pdf", [EspecialidadController::class,"reportePdf"]);
        Route::post("especialidades/reporte-excel", [EspecialidadController::class,"reporteExcel"]);
        Route::apiResource("especialidades", EspecialidadController::class);

        /* RUTAS MODULO TIPOS DE DOCUMENTO*/
        Route::post("tipos-documento/reporte-pdf", [TipoDocumentoController::class,"reportePdf"]);
        Route::post("tipos-documento/reporte-excel", [TipoDocumentoController::class,"reporteExcel"]);
        Route::apiResource("tipos-documento", TipoDocumentoController::class);

        /* RUTAS MODULO CLASIFICACION DE DOCUMENTOS*/
        Route::post("tipos-documento-clasificacion/reporte-pdf", [TipoDocumentoClasificacionController::class,"reportePdf"]);
        Route::post("tipos-documento-clasificacion/reporte-excel", [TipoDocumentoClasificacionController::class,"reporteExcel"]);
        Route::apiResource("tipos-documento-clasificacion", TipoDocumentoClasificacionController::class);
    });
});
