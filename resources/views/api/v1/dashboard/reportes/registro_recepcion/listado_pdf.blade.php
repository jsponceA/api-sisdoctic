<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Registros de Recepción</title>
    <style>
        @page {
            margin: 15px;
            size: A4 landscape;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .titulo {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        /* Estilos para cabecera de secciones */
        .header-section {
            font-weight: bold;
            text-align: center;
            border: 1px solid #000;
            padding: 4px 2px;
            font-size: 7px;
        }

        .header-gray {
            background-color: #d1d5db;
            color: #000;
        }

        .header-yellow {
            background-color: #fde047;
            color: #000;
        }

        .header-blue {
            background-color: #93c5fd;
            color: #000;
        }

        .header-green {
            background-color: #86efac;
            color: #000;
        }

        /* Estilos para cabecera de columnas */
        .col-yellow {
            background-color: #fef08a;
            color: #000;
        }

        .col-blue {
            background-color: #bfdbfe;
            color: #000;
        }

        .col-green {
            background-color: #bbf7d0;
            color: #000;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: left;
            font-size: 6px;
            vertical-align: top;
        }

        th {
            font-weight: bold;
        }

        /* Estilos para celdas de datos */
        .cell-gray {
            background-color: #f3f4f6;
        }

        .cell-yellow {
            background-color: #fef9c3;
        }

        .cell-blue {
            background-color: #dbeafe;
        }

        .cell-green {
            background-color: #dcfce7;
        }

        .badge {
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 6px;
            display: inline-block;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

@php
    // Funciones de cálculo
    $calcularDiasSinResponder = function($fechaEntregaArea, $fechaRespuesta = null) {
        if (!$fechaEntregaArea) return '-';
        $fechaFinal = $fechaRespuesta ? \Carbon\Carbon::parse($fechaRespuesta) : now();
        $fechaEntrega = \Carbon\Carbon::parse($fechaEntregaArea);
        return abs((int) $fechaEntrega->diffInDays($fechaFinal, false));
    };

    $calcularFechaParaResponder = function($fechaEntregaArea, $diasPlazo) {
        if (!$fechaEntregaArea || !$diasPlazo) return '-';
        $fechaEntrega = \Carbon\Carbon::parse($fechaEntregaArea);
        return $fechaEntrega->copy()->addDays($diasPlazo);
    };

    $calcularDiasDefasados = function($fechaParaResponder, $fechaRespuesta = null) {
        if (!$fechaParaResponder || $fechaParaResponder === '-') return '-';
        $fechaFinal = $fechaRespuesta ? \Carbon\Carbon::parse($fechaRespuesta) : now();
        $fechaLimite = is_string($fechaParaResponder) ? \Carbon\Carbon::parse($fechaParaResponder) : $fechaParaResponder;
        if ($fechaFinal->isAfter($fechaLimite)) {
            return abs((int) $fechaLimite->diffInDays($fechaFinal, false));
        }
        return 0;
    };
@endphp

<div class="titulo">Reporte de Registros de Recepción</div>

<table>
    <thead>
    <!-- PRIMERA FILA: Secciones agrupadas -->
    <tr>
        <th rowspan="2" class="header-section header-gray">MES</th>
        <th rowspan="2" class="header-section header-gray">PROYECTO</th>
        <th colspan="10" class="header-section header-yellow">RECEPCIÓN</th>
        <th colspan="5" class="header-section header-blue">RESPUESTA</th>
        <th colspan="6" class="header-section header-green">ESTADO DE RESPUESTA</th>
    </tr>

    <!-- SEGUNDA FILA: Columnas específicas -->
    <tr>
        <!-- RECEPCIÓN (10 columnas) -->
        <th class="header-section col-yellow">Fecha Emisión</th>
        <th class="header-section col-yellow">Fecha Recep.</th>
        <th class="header-section col-yellow">Fecha Entrega Área</th>
        <th class="header-section col-yellow">Tipo Doc</th>
        <th class="header-section col-yellow">N° Doc Recep.</th>
        <th class="header-section col-yellow">Asunto</th>
        <th class="header-section col-yellow">Cant. Docs Adj.</th>
        <th class="header-section col-yellow">Tipo Doc Clasif.</th>
        <th class="header-section col-yellow">Especialidad</th>
        <th class="header-section col-yellow">Destino</th>

        <!-- RESPUESTA (5 columnas) -->
        <th class="header-section col-blue">Fecha</th>
        <th class="header-section col-blue">Cant. Docs</th>
        <th class="header-section col-blue">N° Doc Resp.</th>
        <th class="header-section col-blue">Atención</th>
        <th class="header-section col-blue">Acciones u Obs.</th>

        <!-- ESTADO DE RESPUESTA (6 columnas) -->
        <th class="header-section col-green">Estado</th>
        <th class="header-section col-green">Prioridad</th>
        <th class="header-section col-green">Situación</th>
        <th class="header-section col-green">N° Días sin Resp.</th>
        <th class="header-section col-green">Fecha para Resp.</th>
        <th class="header-section col-green">Días Defas.</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($registros as $r)
        @php
            // Obtener días de plazo del tipo de documento del proyecto
            $diasPlazo = null;
            if ($r->proyecto && $r->tipo_documento_id) {
                $tipoDocProyecto = $r->proyecto->tiposDocumento->firstWhere('tipo_documento_id', $r->tipo_documento_id);
                if ($tipoDocProyecto) {
                    $diasPlazo = $tipoDocProyecto->dias_plazo;
                }
            }

            // Calcular valores
            $diasSinResponder = $calcularDiasSinResponder($r->fecha_entrega_area, $r->fecha_respuesta);
            $fechaParaResponder = $calcularFechaParaResponder($r->fecha_entrega_area, $diasPlazo);
            $diasDefasados = $calcularDiasDefasados($fechaParaResponder, $r->fecha_respuesta);

            // Formatear mes
            $mes = $r->fecha_recepcion ? \Carbon\Carbon::parse($r->fecha_recepcion)->locale('es')->isoFormat('MMMM - dddd') : '-';

            // Contar documentos
            $cantDocAdjuntos = $r->documentosAdjuntos ? $r->documentosAdjuntos->count() : 0;
            $cantDocRespuesta = $r->documentosRespuesta ? $r->documentosRespuesta->count() : 0;

            // Formatear estado
            $estadoTexto = '-';
            if ($r->estado_documento == 'aprobado') {
                $estadoTexto = 'Aprobado';
            } elseif ($r->estado_documento == 'observado') {
                $estadoTexto = 'Observado';
            } elseif ($r->estado_documento == 'en_proceso') {
                $estadoTexto = 'En Proceso';
            }

            // Formatear prioridad
            $prioridadTexto = '-';
            if ($r->prioridad == 1) {
                $prioridadTexto = 'Baja';
            } elseif ($r->prioridad == 2) {
                $prioridadTexto = 'Media';
            } elseif ($r->prioridad == 3) {
                $prioridadTexto = 'Alta';
            }

            // Formatear situación
            $situacionTexto = $r->situacion == 'R' ? 'Respondido' : ($r->situacion == 'SR' ? 'Sin Responder' : '-');

            // Responsables destino
            $destinoTexto = '';
            if ($r->responsablesDestino && $r->responsablesDestino->count() > 0) {
                $destinoTexto = $r->responsablesDestino->map(function($rd) {
                    return $rd->responsable?->nombre_completo ?? '-';
                })->implode(', ');
            }
        @endphp
        <tr>
            <!-- MES -->
            <td class="cell-gray">{{ \Illuminate\Support\Str::limit($mes, 20) }}</td>

            <!-- PROYECTO -->
            <td class="cell-gray">{{ \Illuminate\Support\Str::limit($r->proyecto?->nombre ?? '-', 30) }}</td>

            <!-- ========== RECEPCIÓN ========== -->
            <td class="cell-yellow">{{ $r->fecha_emision_format ?? '-' }}</td>
            <td class="cell-yellow">{{ $r->fecha_recepcion_format ?? '-' }}</td>
            <td class="cell-yellow">{{ $r->fecha_entrega_area_format ?? '-' }}</td>
            <td class="cell-yellow">{{ \Illuminate\Support\Str::limit($r->tipoDocumento?->nombre ?? '-', 15) }}</td>
            <td class="cell-yellow font-bold">{{ $r->num_doc_recep ?? '-' }}</td>
            <td class="cell-yellow">{{ \Illuminate\Support\Str::limit($r->asunto ?? '-', 30) }}</td>
            <td class="cell-yellow text-center">{{ $cantDocAdjuntos }}</td>
            <td class="cell-yellow">{{ \Illuminate\Support\Str::limit($r->tipoDocumentoClasificacion?->nombre ?? '-', 15) }}</td>
            <td class="cell-yellow">{{ \Illuminate\Support\Str::limit($r->especialidad?->nombre ?? '-', 15) }}</td>
            <td class="cell-yellow">{{ \Illuminate\Support\Str::limit($destinoTexto ?: ($r->destino ?? '-'), 20) }}</td>

            <!-- ========== RESPUESTA ========== -->
            <td class="cell-blue">{{ $r->fecha_respuesta_format ?? '-' }}</td>
            <td class="cell-blue text-center">{{ $cantDocRespuesta }}</td>
            <td class="cell-blue">{{ $r->num_docs_resp ?? '-' }}</td>
            <td class="cell-blue">{{ \Illuminate\Support\Str::limit($r->atencion ?? '-', 20) }}</td>
            <td class="cell-blue">{{ \Illuminate\Support\Str::limit($r->acciones_observaciones ?? '-', 25) }}</td>

            <!-- ========== ESTADO DE RESPUESTA ========== -->
            <td class="cell-green">{{ $estadoTexto }}</td>
            <td class="cell-green">{{ $prioridadTexto }}</td>
            <td class="cell-green">
                @if($r->situacion == 'R')
                    <span class="badge badge-success">R</span>
                @elseif($r->situacion == 'SR')
                    <span class="badge badge-danger">SR</span>
                @else
                    -
                @endif
            </td>
            <td class="cell-green text-center">{{ $diasSinResponder !== '-' ? $diasSinResponder : '-' }}</td>
            <td class="cell-green">{{ $fechaParaResponder !== '-' ? $fechaParaResponder->format('d/m/Y') : '-' }}</td>
            <td class="cell-green text-center">{{ $diasDefasados !== '-' ? $diasDefasados : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
