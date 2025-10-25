<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Registro de Recepción</title>
    <style>
        @page {
            margin: 80px 25px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }

        .titulo-principal {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .section-header {
            background-color: #2c61ed;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }

        .label-cell {
            width: 30%;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .content-cell {
            width: 70%;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
        }

        .info-row td {
            padding: 8px;
        }

        .text-area {
            min-height: 60px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
{{--<header>
    <img src="{{public_path('img/logo_mincul.jpg')}}" style="width: 377px;height: 45px" alt="Logo Mincul">
    <img src="{{public_path('img/logo_puruchuco.png')}}" style="width: 111px;height: 60px;float: right" alt="Logo Puruchuco">
</header>--}}

@php
    // Función para calcular días sin responder
    $calcularDiasSinResponder = function($fechaEntregaArea, $fechaRespuesta = null) {
        if (!$fechaEntregaArea) return '-';
        $fechaFinal = $fechaRespuesta ? \Carbon\Carbon::parse($fechaRespuesta) : now();
        $fechaEntrega = \Carbon\Carbon::parse($fechaEntregaArea);
        return abs((int) $fechaEntrega->diffInDays($fechaFinal, false));
    };

    // Función para calcular fecha para responder
    $calcularFechaParaResponder = function($fechaEntregaArea, $diasPlazo) {
        if (!$fechaEntregaArea || !$diasPlazo) return '-';
        $fechaEntrega = \Carbon\Carbon::parse($fechaEntregaArea);
        return $fechaEntrega->copy()->addDays($diasPlazo);
    };

    // Función para calcular días defasados
    $calcularDiasDefasados = function($fechaParaResponder, $fechaRespuesta = null) {
        if (!$fechaParaResponder || $fechaParaResponder === '-') return '-';
        $fechaFinal = $fechaRespuesta ? \Carbon\Carbon::parse($fechaRespuesta) : now();
        $fechaLimite = is_string($fechaParaResponder) ? \Carbon\Carbon::parse($fechaParaResponder) : $fechaParaResponder;
        if ($fechaFinal->isAfter($fechaLimite)) {
            return abs((int) $fechaLimite->diffInDays($fechaFinal, false));
        }
        return 0;
    };

    // Obtener días de plazo del tipo de documento del proyecto
    $diasPlazo = null;
    if ($registro->proyecto && $registro->tipo_documento_id) {
        $tipoDocProyecto = $registro->proyecto->tiposDocumento->firstWhere('tipo_documento_id', $registro->tipo_documento_id);
        if ($tipoDocProyecto) {
            $diasPlazo = $tipoDocProyecto->dias_plazo;
        }
    }

    // Calcular valores
    $diasSinResponder = $calcularDiasSinResponder($registro->fecha_entrega_area, $registro->fecha_respuesta);
    $fechaParaResponder = $calcularFechaParaResponder($registro->fecha_entrega_area, $diasPlazo);
    $diasDefasados = $calcularDiasDefasados($fechaParaResponder, $registro->fecha_respuesta);
@endphp

<div class="titulo-principal">FICHA DE REGISTRO DE RECEPCIÓN</div>

<!-- I. PROYECTO ASOCIADO -->
<table>
    <tr>
        <td colspan="4" class="section-header">I. PROYECTO ASOCIADO</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell" style="width: 70px">Código de Proyecto:</td>
        <td class="content-cell" style="width: 100px">{{ $registro->proyecto?->codigo_proyecto ?? '-' }}</td>
        <td class="label-cell"  style="width: 140px">Nombre del Proyecto:</td>
        <td class="content-cell">{{ $registro->proyecto?->nombre ?? '-' }}</td>
    </tr>
</table>

<!-- II. DATOS DE RECEPCIÓN -->
<table>
    <tr>
        <td colspan="4" class="section-header">II. DATOS DE RECEPCIÓN</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha de Emisión:</td>
        <td class="content-cell">{{ $registro->fecha_emision_format ?? '-' }}</td>
        <td class="label-cell">Fecha de Recepción:</td>
        <td class="content-cell">{{ $registro->fecha_recepcion_format ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha Entrega al Área:</td>
        <td class="content-cell">{{ $registro->fecha_entrega_area_format ?? '-' }}</td>
        <td class="label-cell">Tipo de Documento:</td>
        <td class="content-cell">{{ $registro->tipoDocumento?->nombre ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">N° Doc Recepción:</td>
        <td class="content-cell" colspan="3">{{ $registro->num_doc_recep ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Asunto:</td>
        <td colspan="3" class="content-cell">{{ $registro->asunto ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Tipo Doc Clasificación:</td>
        <td class="content-cell" colspan="3">{{ $registro->tipoDocumentoClasificacion?->nombre ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Especialidad:</td>
        <td class="content-cell" colspan="3">{{ $registro->especialidad?->nombre ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Responsables Destino:</td>
        <td colspan="3" class="content-cell">
            @if($registro->responsablesDestino && $registro->responsablesDestino->count() > 0)
                @foreach($registro->responsablesDestino as $rd)
                    <div>• {{ $rd->responsable?->nombre_completo ?? '-' }}</div>
                @endforeach
            @else
                -
            @endif
        </td>
    </tr>
</table>

<!-- III. DATOS DE RESPUESTA -->
<table>
    <tr>
        <td colspan="4" class="section-header">III. DATOS DE RESPUESTA</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha de Respuesta:</td>
        <td class="content-cell">{{ $registro->fecha_respuesta_format ?? '-' }}</td>
        <td class="label-cell">N° Docs Respuesta:</td>
        <td class="content-cell">{{ $registro->num_docs_resp ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Atención:</td>
        <td colspan="3" class="content-cell">{{ $registro->atencion ?? '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Acciones y Observaciones:</td>
        <td colspan="3" class="content-cell text-area">{{ $registro->acciones_observaciones ?? '-' }}</td>
    </tr>
</table>

<!-- IV. ESTADO DE RESPUESTA -->
<table>
    <tr>
        <td colspan="4" class="section-header">IV. ESTADO DE RESPUESTA</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Estado Documento:</td>
        <td class="content-cell">
            @if($registro->estado_documento == "aprobado")
                Aprobado
            @elseif($registro->estado_documento == "observado")
                Observado (No Aprobado)
            @elseif($registro->estado_documento == "en_proceso")
                En Proceso
            @else
                -
            @endif
        </td>
        <td class="label-cell">Prioridad:</td>
        <td class="content-cell">
            @if($registro->prioridad == 1)
                Baja
            @elseif($registro->prioridad == 2)
                Media
            @elseif($registro->prioridad == 3)
                Alta
            @else
                -
            @endif
        </td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Situación:</td>
        <td class="content-cell">
            @if($registro->situacion == 'R')
                <span class="badge badge-success">Respondido</span>
            @elseif($registro->situacion == 'SR')
                <span class="badge badge-danger">Sin Responder</span>
            @else
                -
            @endif
        </td>
        <td class="label-cell">N° Días Sin Responder:</td>
        <td class="content-cell">
            @if($diasSinResponder !== '-')
                {{ $diasSinResponder }} días
            @else
                -
            @endif
        </td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha Para Responder:</td>
        <td class="content-cell">
            @if($fechaParaResponder !== '-')
                {{ $fechaParaResponder->format('d/m/Y') }}
            @else
                -
            @endif
        </td>
        <td class="label-cell">Días Defasados:</td>
        <td class="content-cell">
            @if($diasDefasados !== '-')
                {{ $diasDefasados }} días
            @else
                -
            @endif
        </td>
    </tr>
</table>

<!-- V. DOCUMENTOS ADJUNTOS -->
@if($registro->documentosAdjuntos && $registro->documentosAdjuntos->count() > 0)
<table>
    <tr>
        <td colspan="2" class="section-header">V. DOCUMENTOS ADJUNTOS</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">#</th>
        <th style="background-color: #f0f0f0;">Archivo</th>
    </tr>
    @foreach($registro->documentosAdjuntos as $index => $doc)
    <tr>
        <td style="text-align: center;">{{ $index + 1 }}</td>
        <td><a href="{{ $doc->archivo_url }}" target="_blank">{{ $doc->archivo_url }}</a> </td>
    </tr>
    @endforeach
</table>
@endif

<!-- VI. DOCUMENTOS DE RESPUESTA -->
@if($registro->documentosRespuesta && $registro->documentosRespuesta->count() > 0)
<table>
    <tr>
        <td colspan="2" class="section-header">VI. DOCUMENTOS DE RESPUESTA</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">#</th>
        <th style="background-color: #f0f0f0;">Archivo</th>
    </tr>
    @foreach($registro->documentosRespuesta as $index => $doc)
    <tr>
        <td style="text-align: center;">{{ $index + 1 }}</td>
        <td><a href="{{ $doc->archivo_url }}" target="_blank">{{ $doc->archivo_url }}</a> </td>
    </tr>
    @endforeach
</table>
@endif

</body>
</html>
