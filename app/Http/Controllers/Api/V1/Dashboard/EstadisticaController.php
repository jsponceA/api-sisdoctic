<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Proyecto;
use App\Models\RegistroRecepcion;
use App\Models\Responsable;
use App\Models\TipoDocumento;
use App\Models\TipoDocumentoClasificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    /**
     * KPI General - Resumen de totales
     */
    public function kpiGeneral(Request $request)
    {
        $totalProyectos = Proyecto::count();
        $proyectosActivos = Proyecto::whereNull('fecha_fin')
            ->orWhere('fecha_fin', '>=', now())
            ->count();

        $totalRecepciones = RegistroRecepcion::count();
        $recepcionesRespondidas = RegistroRecepcion::where('situacion', 'R')->count();
        $recepcionesSinResponder = RegistroRecepcion::where('situacion', 'SR')->count();
        $recepcionesPendientes = RegistroRecepcion::whereNull('situacion')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'proyectos' => [
                    'total' => $totalProyectos,
                    'activos' => $proyectosActivos,
                    'finalizados' => $totalProyectos - $proyectosActivos,
                ],
                'recepciones' => [
                    'total' => $totalRecepciones,
                    'respondidas' => $recepcionesRespondidas,
                    'sin_responder' => $recepcionesSinResponder,
                    'pendientes' => $recepcionesPendientes,
                    'porcentaje_respuesta' => $totalRecepciones > 0
                        ? round(($recepcionesRespondidas / $totalRecepciones) * 100, 2)
                        : 0,
                ],
            ]
        ]);
    }

    /**
     * KPI - Proyectos por Tipo de Documento
     */
    public function proyectosPorTipoDocumento(Request $request)
    {
        $data = DB::table('proyectos as p')
            ->join('proyecto_tipos_documento as ptd', 'p.id', '=', 'ptd.proyecto_id')
            ->join('tipos_documento as td', 'ptd.tipo_documento_id', '=', 'td.id')
            ->select(
                'td.id',
                'td.nombre as tipo_documento',
                DB::raw('COUNT(DISTINCT p.id) as total_proyectos'),
                DB::raw('AVG(ptd.dias_plazo) as promedio_dias_plazo')
            )
            ->whereNull('p.deleted_at')
            ->whereNull('td.deleted_at')
            ->groupBy('td.id', 'td.nombre')
            ->orderBy('total_proyectos', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Recepciones por Tipo de Documento
     */
    public function recepcionesPorTipoDocumento(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = DB::table('registro_recepciones as rr')
            ->join('tipos_documento as td', 'rr.tipo_documento_id', '=', 'td.id')
            ->select(
                'td.id',
                'td.nombre as tipo_documento',
                DB::raw('COUNT(rr.id) as total_recepciones'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder'),
                DB::raw('ROUND(AVG(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) * 100, 2) as porcentaje_respuesta')
            )
            ->whereNull('rr.deleted_at')
            ->whereNull('td.deleted_at');

        if ($fechaInicio) {
            $query->where('rr.fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('rr.fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->groupBy('td.id', 'td.nombre')
            ->orderByDesc('total_recepciones')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Recepciones por Formato de Documento (Clasificación)
     */
    public function recepcionesPorFormatoDocumento(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = DB::table('registro_recepciones as rr')
            ->leftJoin('tipos_documento_clasificacion as tdc', 'rr.tipo_documento_clasificacion_id', '=', 'tdc.id')
            ->select(
                'tdc.id',
                DB::raw('COALESCE(tdc.nombre, \'Sin Clasificar\') as formato_documento'),
                DB::raw('COUNT(rr.id) as total_recepciones'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder')
            )
            ->whereNull('rr.deleted_at');

        if ($fechaInicio) {
            $query->where('rr.fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('rr.fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->groupBy('tdc.id', 'tdc.nombre')
            ->orderByDesc('total_recepciones')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Recepciones por Proyecto
     */
    public function recepcionesPorProyecto(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = DB::table('registro_recepciones as rr')
            ->join('proyectos as p', 'rr.proyecto_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.codigo_proyecto',
                'p.nombre as proyecto',
                DB::raw('COUNT(rr.id) as total_recepciones'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder'),
                DB::raw('SUM(CASE WHEN rr.estado_documento = \'aprobado\' THEN 1 ELSE 0 END) as aprobados'),
                DB::raw('SUM(CASE WHEN rr.estado_documento = \'observado\' THEN 1 ELSE 0 END) as observados'),
                DB::raw('ROUND(AVG(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) * 100, 2) as porcentaje_respuesta')
            )
            ->whereNull('rr.deleted_at')
            ->whereNull('p.deleted_at');

        if ($fechaInicio) {
            $query->where('rr.fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('rr.fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->groupBy('p.id', 'p.codigo_proyecto', 'p.nombre')
            ->orderByDesc('total_recepciones')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Recepciones por Especialidad
     */
    public function recepcionesPorEspecialidad(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = DB::table('registro_recepciones as rr')
            ->leftJoin('especialidades as e', 'rr.especialidad_id', '=', 'e.id')
            ->select(
                'e.id',
                DB::raw('COALESCE(e.nombre, \'Sin Especialidad\') as especialidad'),
                DB::raw('COUNT(rr.id) as total_recepciones'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder'),
                DB::raw('ROUND(AVG(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) * 100, 2) as porcentaje_respuesta')
            )
            ->whereNull('rr.deleted_at');

        if ($fechaInicio) {
            $query->where('rr.fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('rr.fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->groupBy('e.id', 'e.nombre')
            ->orderByDesc('total_recepciones')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Recepciones por Estado de Documento
     */
    public function recepcionesPorEstado(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = RegistroRecepcion::select(
                'estado_documento',
                DB::raw('COUNT(*) as total')
            )
            ->whereNull('deleted_at');

        if ($fechaInicio) {
            $query->where('fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('fecha_recepcion', '<=', $fechaFin);
        }

        // Obtener el total para calcular porcentajes
        $totalRecepciones = (clone $query)->count();

        $data = $query->groupBy('estado_documento')
            ->get()
            ->map(function($item) use ($totalRecepciones) {
                $estados = [
                    'aprobado' => 'Aprobado',
                    'observado' => 'Observado',
                    'en_proceso' => 'En Proceso',
                ];
                return [
                    'estado' => $estados[$item->estado_documento] ?? 'Sin Estado',
                    'total' => $item->total,
                    'porcentaje' => $totalRecepciones > 0 ? round(($item->total / $totalRecepciones) * 100, 2) : 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Recepciones por Prioridad
     */
    public function recepcionesPorPrioridad(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = RegistroRecepcion::select(
                'prioridad',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder')
            )
            ->whereNull('deleted_at')
            ->whereNotNull('prioridad');

        if ($fechaInicio) {
            $query->where('fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->groupBy('prioridad')
            ->orderBy('prioridad')
            ->get()
            ->map(function($item) {
                $prioridades = [
                    1 => 'Baja',
                    2 => 'Media',
                    3 => 'Alta',
                ];
                return [
                    'prioridad' => $prioridades[$item->prioridad] ?? 'Sin Prioridad',
                    'total' => $item->total,
                    'respondidas' => $item->respondidas,
                    'sin_responder' => $item->sin_responder,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Tiempo Promedio de Respuesta
     */
    public function tiempoPromedioRespuesta(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = DB::table('registro_recepciones')
            ->select(
                DB::raw('AVG(fecha_respuesta::date - fecha_entrega_area::date) as promedio_dias'),
                DB::raw('MIN(fecha_respuesta::date - fecha_entrega_area::date) as minimo_dias'),
                DB::raw('MAX(fecha_respuesta::date - fecha_entrega_area::date) as maximo_dias'),
                DB::raw('COUNT(*) as total_respondidas')
            )
            ->whereNull('deleted_at')
            ->whereNotNull('fecha_respuesta')
            ->whereNotNull('fecha_entrega_area')
            ->where('situacion', 'R');

        if ($fechaInicio) {
            $query->where('fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->first();

        return response()->json([
            'success' => true,
            'data' => [
                'promedio_dias' => round($data->promedio_dias ?? 0, 2),
                'minimo_dias' => $data->minimo_dias ?? 0,
                'maximo_dias' => $data->maximo_dias ?? 0,
                'total_respondidas' => $data->total_respondidas ?? 0,
            ]
        ]);
    }

    /**
     * KPI - Recepciones con Retraso (Días Defasados)
     */
    public function recepcionesConRetraso(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        // Recepciones con fecha para responder vencida y sin respuesta
        $query = DB::table('registro_recepciones as rr')
            ->join('proyectos as p', 'rr.proyecto_id', '=', 'p.id')
            ->join('proyecto_tipos_documento as ptd', function($join) {
                $join->on('p.id', '=', 'ptd.proyecto_id')
                     ->on('rr.tipo_documento_id', '=', 'ptd.tipo_documento_id');
            })
            ->select(
                'rr.id',
                'p.codigo_proyecto',
                'p.nombre as proyecto',
                'rr.num_doc_recep',
                'rr.asunto',
                DB::raw('rr.fecha_entrega_area::date as fecha_entrega_area'),
                'ptd.dias_plazo',
                DB::raw('(rr.fecha_entrega_area::date + ptd.dias_plazo * INTERVAL \'1 day\')::date as fecha_limite'),
                DB::raw('CURRENT_DATE - (rr.fecha_entrega_area::date + ptd.dias_plazo * INTERVAL \'1 day\')::date as dias_retraso')
            )
            ->whereNull('rr.deleted_at')
            ->whereNull('p.deleted_at')
            ->where('rr.situacion', 'SR')
            ->whereNotNull('rr.fecha_entrega_area')
            ->whereRaw('(rr.fecha_entrega_area::date + ptd.dias_plazo * INTERVAL \'1 day\')::date < CURRENT_DATE');

        if ($fechaInicio) {
            $query->where('rr.fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('rr.fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->orderByDesc('dias_retraso')
            ->limit(20)
            ->get();

        $totalConRetraso = DB::table('registro_recepciones as rr')
            ->join('proyecto_tipos_documento as ptd', function($join) {
                $join->on('rr.proyecto_id', '=', 'ptd.proyecto_id')
                     ->on('rr.tipo_documento_id', '=', 'ptd.tipo_documento_id');
            })
            ->whereNull('rr.deleted_at')
            ->where('rr.situacion', 'SR')
            ->whereNotNull('rr.fecha_entrega_area')
            ->whereRaw('(rr.fecha_entrega_area::date + ptd.dias_plazo * INTERVAL \'1 day\')::date < CURRENT_DATE')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_con_retraso' => $totalConRetraso,
                'recepciones' => $data,
            ]
        ]);
    }

    /**
     * KPI - Tendencia Mensual de Recepciones
     */
    public function tendenciaMensualRecepciones(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $data = DB::table('registro_recepciones')
            ->select(
                DB::raw('EXTRACT(MONTH FROM fecha_recepcion) as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder')
            )
            ->whereNull('deleted_at')
            ->whereRaw('EXTRACT(YEAR FROM fecha_recepcion) = ?', [$anio])
            ->groupBy(DB::raw('EXTRACT(MONTH FROM fecha_recepcion)'))
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                $meses = [
                    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                ];
                return [
                    'mes' => $meses[$item->mes],
                    'mes_numero' => $item->mes,
                    'total' => $item->total,
                    'respondidas' => $item->respondidas,
                    'sin_responder' => $item->sin_responder,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * KPI - Top Proyectos con Mayor Actividad
     */
    public function topProyectosActividad(Request $request)
    {
        $limit = $request->get('limit', 10);
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = DB::table('proyectos as p')
            ->leftJoin('registro_recepciones as rr', function($join) {
                $join->on('p.id', '=', 'rr.proyecto_id')
                     ->whereNull('rr.deleted_at');
            })
            ->select(
                'p.id',
                'p.codigo_proyecto',
                'p.nombre',
                DB::raw('COUNT(rr.id) as total_recepciones'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) as respondidas'),
                DB::raw('SUM(CASE WHEN rr.situacion = \'SR\' THEN 1 ELSE 0 END) as sin_responder'),
                DB::raw('ROUND(AVG(CASE WHEN rr.situacion = \'R\' THEN 1 ELSE 0 END) * 100, 2) as porcentaje_respuesta')
            )
            ->whereNull('p.deleted_at');

        if ($fechaInicio) {
            $query->where('rr.fecha_recepcion', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('rr.fecha_recepcion', '<=', $fechaFin);
        }

        $data = $query->groupBy('p.id', 'p.codigo_proyecto', 'p.nombre')
            ->orderByDesc('total_recepciones')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
