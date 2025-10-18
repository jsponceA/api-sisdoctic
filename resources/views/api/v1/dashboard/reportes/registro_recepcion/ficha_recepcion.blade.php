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

<div class="titulo-principal">FICHA DE REGISTRO DE RECEPCIÓN</div>

<!-- I. PROYECTO ASOCIADO -->
<table>
    <tr>
        <td colspan="4" class="section-header">I. PROYECTO ASOCIADO</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Código de Proyecto:</td>
        <td colspan="3" class="content-cell">{{ $registro->proyecto?->codigo_proyecto }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Nombre del Proyecto:</td>
        <td colspan="3" class="content-cell">{{ $registro->proyecto?->nombre }}</td>
    </tr>
</table>

<!-- II. DATOS DE RECEPCIÓN -->
<table>
    <tr>
        <td colspan="4" class="section-header">II. DATOS DE RECEPCIÓN</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha de Emisión:</td>
        <td class="content-cell">{{ $registro->fecha_emision_format }}</td>
        <td class="label-cell">Fecha de Recepción:</td>
        <td class="content-cell">{{ $registro->fecha_recepcion_format }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha Entrega al Área:</td>
        <td class="content-cell">{{ $registro->fecha_entrega_area_format }}</td>
        <td class="label-cell">Tipo de Documento:</td>
        <td class="content-cell">{{ $registro->tipoDocumento?->nombre }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">N° Doc Recepción:</td>
        <td class="content-cell" colspan="3">{{ $registro->num_doc_recep }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Asunto:</td>
        <td colspan="3" class="content-cell">{{ $registro->asunto }}</td>
    </tr>

    <tr class="info-row">
        <td class="label-cell">Tipo Doc Clasificación:</td>
        <td class="content-cell">{{ $registro->tipoDocumentoClasificacion?->nombre }}</td>
        <td class="label-cell">Especialidad:</td>
        <td class="content-cell">{{ $registro->especialidad?->nombre }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Destino:</td>
        <td colspan="3" class="content-cell">{{ $registro->destino }}</td>
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
        <td colspan="3" class="content-cell">{{ $registro->atencion }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Acciones y Observaciones:</td>
        <td colspan="3" class="content-cell text-area">{{ $registro->acciones_observaciones }}</td>
    </tr>
</table>

<!-- IV. ESTADO DE RESPUESTA -->
<table>
    <tr>
        <td colspan="4" class="section-header">IV. ESTADO DE RESPUESTA</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Situación:</td>
        <td class="content-cell">
            @if($registro->situacion == 'R')
                <span class="badge badge-success">Respondido</span>
            @else
                <span class="badge badge-danger">Sin Responder</span>
            @endif
        </td>
        <td class="label-cell">Prioridad:</td>
        <td class="content-cell">
            @if($registro->prioridad == 1)
                Alta
            @elseif($registro->prioridad == 2)
                Media
            @elseif($registro->prioridad == 3)
                Baja
            @else
                -
            @endif
        </td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">N° Días Sin Responder:</td>
        <td class="content-cell">{{ $registro->num_dias_sin_responder ?? '-' }}</td>
        <td class="label-cell">Fecha Para Responder:</td>
        <td class="content-cell">{{ $registro->fecha_para_responder_format ?? '-' }}</td>
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
        <td>{{ $doc->nombre_original }}</td>
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
        <td>{{ $doc->nombre_original }}</td>
    </tr>
    @endforeach
</table>
@endif



</body>
</html>
