<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Denominacion;
use App\Models\EstadoConservacion;
use App\Models\Intervencion;
use App\Models\Responsable;
use App\Models\Sala;
use App\Models\TipoMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    /**
     * Resumen general del sistema - Cards principales
     */
    public function resumenGeneral()
    {
        try {
            $data = [
                'total_articulos' => Articulo::count(),
                'total_intervenciones' => Intervencion::count(),
                'total_responsables' => Responsable::count(),
                'total_usuarios' => User::count(),
                'total_salas' => Sala::count(),
                'total_categorias' => Categoria::count(),
                'total_denominaciones' => Denominacion::count(),
                'total_tipos_material' => TipoMaterial::count(),
                'total_estados_conservacion' => EstadoConservacion::count(),

                // Estadísticas adicionales
                'articulos_mes_actual' => Articulo::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'intervenciones_mes_actual' => Intervencion::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'intervenciones_pendientes' => Intervencion::whereNull('fecha_entrega')->count(),
                'intervenciones_completadas' => Intervencion::whereNotNull('fecha_entrega')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener resumen general',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Artículos por tipo de material - Gráfico de barras/pie
     */
    public function articulosPorTipoMaterial()
    {
        try {
            $data = Articulo::select('tipo_material_id', DB::raw('count(*) as total'))
                ->with('tipoMaterial:id,nombre')
                ->groupBy('tipo_material_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->tipoMaterial?->nombre ?? 'Sin tipo',
                        'value' => $item->total,
                        'tipo_material_id' => $item->tipo_material_id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículos por tipo de material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Artículos por estado de conservación - Gráfico de barras/pie
     */
    public function articulosPorEstadoConservacion()
    {
        try {
            $data = Articulo::select('estado_conservacion_id', DB::raw('count(*) as total'))
                ->with('estadoConservacion:id,nombre')
                ->groupBy('estado_conservacion_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->estadoConservacion?->nombre ?? 'Sin estado',
                        'value' => $item->total,
                        'estado_conservacion_id' => $item->estado_conservacion_id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículos por estado de conservación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Artículos por sala - Gráfico de barras/pie
     */
    public function articulosPorSala()
    {
        try {
            $data = Articulo::select('sala_id', DB::raw('count(*) as total'))
                ->with('sala:id,nombre')
                ->groupBy('sala_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->sala?->nombre ?? 'Sin sala',
                        'value' => $item->total,
                        'sala_id' => $item->sala_id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículos por sala',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Artículos por denominación - Gráfico de barras/pie
     */
    public function articulosPorDenominacion()
    {
        try {
            $data = Articulo::select('denominacion_id', DB::raw('count(*) as total'))
                ->with('denominacion:id,nombre')
                ->groupBy('denominacion_id')
                ->orderBy('total', 'DESC')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->denominacion?->nombre ?? 'Sin denominación',
                        'value' => $item->total,
                        'denominacion_id' => $item->denominacion_id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículos por denominación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Intervenciones por categoría - Gráfico de barras/pie
     */
    public function intervencionesPorCategoria()
    {
        try {
            $data = Intervencion::select('categoria_id', DB::raw('count(*) as total'))
                ->with('categoria:id,nombre')
                ->groupBy('categoria_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->categoria?->nombre ?? 'Sin categoría',
                        'value' => $item->total,
                        'categoria_id' => $item->categoria_id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener intervenciones por categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Intervenciones por conservador - Gráfico de barras
     */
    public function intervencionesPorConservador()
    {
        try {
            $data = Intervencion::select('conservador_responsable_id', DB::raw('count(*) as total'))
                ->with('conservadorResponsable:id,nombres,apellidos')
                ->groupBy('conservador_responsable_id')
                ->orderBy('total', 'DESC')
                ->get()
                ->map(function ($item) {
                    $nombre = $item->conservadorResponsable
                        ? "{$item->conservadorResponsable->nombres} {$item->conservadorResponsable->apellidos}"
                        : 'Sin conservador';

                    return [
                        'label' => $nombre,
                        'value' => $item->total,
                        'conservador_id' => $item->conservador_responsable_id
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener intervenciones por conservador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Intervenciones por mes - Gráfico de líneas/barras
     */
    public function intervencionesPorMes(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);

            $data = Intervencion::select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                    DB::raw('count(*) as total')
                )
                ->whereYear('created_at', $year)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get()
                ->map(function ($item) {
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];

                    return [
                        'mes' => $item->mes,
                        'label' => $meses[$item->mes],
                        'value' => $item->total
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data,
                'year' => $year
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener intervenciones por mes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Artículos creados por mes - Gráfico de líneas/barras
     */
    public function articulosPorMes(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);

            $data = Articulo::select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                    DB::raw('count(*) as total')
                )
                ->whereYear('created_at', $year)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get()
                ->map(function ($item) {
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];

                    return [
                        'mes' => $item->mes,
                        'label' => $meses[$item->mes],
                        'value' => $item->total
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data,
                'year' => $year
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener artículos por mes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estado de intervenciones - Gráfico de dona/pie
     */
    public function estadoIntervenciones()
    {
        try {
            $pendientes = Intervencion::whereNull('fecha_entrega')->count();
            $completadas = Intervencion::whereNotNull('fecha_entrega')->count();
            $enProceso = Intervencion::whereNotNull('fecha_recepcion')
                ->whereNull('fecha_entrega')
                ->count();

            $data = [
                ['label' => 'Pendientes', 'value' => $pendientes, 'color' => '#FFA726'],
                ['label' => 'En Proceso', 'value' => $enProceso, 'color' => '#42A5F5'],
                ['label' => 'Completadas', 'value' => $completadas, 'color' => '#66BB6A'],
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estado de intervenciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Top responsables con más artículos asignados - Tabla
     */
    public function topResponsablesArticulos()
    {
        try {
            $data = DB::table('articulo_responsables')
                ->select('responsable_id', DB::raw('count(*) as total'))
                ->groupBy('responsable_id')
                ->orderBy('total', 'DESC')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    $responsable = Responsable::find($item->responsable_id);
                    return [
                        'responsable_id' => $item->responsable_id,
                        'nombre' => $responsable ? "{$responsable->nombres} {$responsable->apellidos}" : 'N/A',
                        'correo' => $responsable?->correo ?? 'N/A',
                        'total_articulos' => $item->total
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener top responsables',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Últimos artículos creados - Tabla
     */
    public function ultimosArticulos(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            $data = Articulo::with([
                    'tipoMaterial:id,nombre',
                    'denominacion:id,nombre',
                    'estadoConservacion:id,nombre',
                    'sala:id,nombre'
                ])
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get()
                ->map(function ($articulo) {
                    return [
                        'id' => $articulo->id,
                        'numero_ficha' => $articulo->numero_ficha,
                        'codigo_inventario' => $articulo->codigo_inventario_objeto,
                        'tipo_material' => $articulo->tipoMaterial?->nombre,
                        'denominacion' => $articulo->denominacion?->nombre,
                        'estado_conservacion' => $articulo->estadoConservacion?->nombre,
                        'sala' => $articulo->sala?->nombre,
                        'fecha_creacion' => $articulo->fecha_creacion_format
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener últimos artículos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Últimas intervenciones - Tabla
     */
    public function ultimasIntervenciones(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            $data = Intervencion::with([
                    'categoria:id,nombre',
                    'denominacion:id,nombre',
                    'conservadorResponsable:id,nombres,apellidos'
                ])
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get()
                ->map(function ($intervencion) {
                    return [
                        'id' => $intervencion->id,
                        'numero_ficha' => $intervencion->numero_ficha,
                        'numero_inventario' => $intervencion->numero_inventario,
                        'categoria' => $intervencion->categoria?->nombre,
                        'denominacion' => $intervencion->denominacion?->nombre,
                        'conservador' => $intervencion->conservadorResponsable?->nombre_completo,
                        'fecha_recepcion' => $intervencion->fecha_recepcion_format,
                        'fecha_entrega' => $intervencion->fecha_entrega_format,
                        'estado' => $intervencion->fecha_entrega ? 'Completada' : 'En Proceso'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener últimas intervenciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Distribución de integridad en artículos - Gráfico de barras
     */
    public function distribucionIntegridad()
    {
        try {
            $data = Articulo::select('estado_integridad', DB::raw('count(*) as total'))
                ->whereNotNull('estado_integridad')
                ->groupBy('estado_integridad')
                ->orderBy('total', 'DESC')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->estado_integridad ?: 'Sin especificar',
                        'value' => $item->total
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener distribución de integridad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Comparativa anual - Gráfico de líneas
     */
    public function comparativaAnual(Request $request)
    {
        try {
            $years = $request->get('years', [now()->year - 1, now()->year]);

            $dataArticulos = [];
            $dataIntervenciones = [];

            foreach ($years as $year) {
                $articulosPorMes = Articulo::select(
                        DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                        DB::raw('count(*) as total')
                    )
                    ->whereYear('created_at', $year)
                    ->groupBy('mes')
                    ->pluck('total', 'mes');

                $intervencionesPorMes = Intervencion::select(
                        DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                        DB::raw('count(*) as total')
                    )
                    ->whereYear('created_at', $year)
                    ->groupBy('mes')
                    ->pluck('total', 'mes');

                $mesesData = [];
                for ($mes = 1; $mes <= 12; $mes++) {
                    $mesesData[] = [
                        'mes' => $mes,
                        'articulos' => $articulosPorMes[$mes] ?? 0,
                        'intervenciones' => $intervencionesPorMes[$mes] ?? 0
                    ];
                }

                $dataArticulos[$year] = $mesesData;
                $dataIntervenciones[$year] = $mesesData;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'articulos' => $dataArticulos,
                    'intervenciones' => $dataIntervenciones
                ],
                'years' => $years
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comparativa anual',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard completo - Todos los datos principales
     */
    public function dashboardCompleto(Request $request)
    {
        try {
            $year = $request->get('year', now()->year);

            return response()->json([
                'success' => true,
                'data' => [
                    'resumen_general' => $this->resumenGeneral()->getData()->data,
                    'articulos_tipo_material' => $this->articulosPorTipoMaterial()->getData()->data,
                    'articulos_estado_conservacion' => $this->articulosPorEstadoConservacion()->getData()->data,
                    'intervenciones_categoria' => $this->intervencionesPorCategoria()->getData()->data,
                    'estado_intervenciones' => $this->estadoIntervenciones()->getData()->data,
                    'ultimos_articulos' => $this->ultimosArticulos($request)->getData()->data,
                    'ultimas_intervenciones' => $this->ultimasIntervenciones($request)->getData()->data,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener dashboard completo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
