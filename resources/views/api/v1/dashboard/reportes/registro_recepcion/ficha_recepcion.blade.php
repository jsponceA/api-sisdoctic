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

        footer {
            position: fixed;
            bottom: -60px;
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

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
    </style>
</head>
<body>
<header>
    <img src="{{public_path('img/logo_mincul.jpg')}}" style="width: 377px;height: 45px">
    <img src="{{public_path('img/logo_puruchuco.png')}}" style="width: 111px;height: 60px;float: right">
</header>

<div class="titulo-principal">FICHA DE REGISTRO DE RECEPCIÓN</div>

<!-- I. DATOS DE IDENTIFICACIÓN -->
<table>
    <tr>
        <td colspan="4" class="section-header">I. DATOS DE IDENTIFICACIÓN</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Número de Recepción:</td>
        <td class="content-cell">{{ $registro->numero_recepcion }}</td>
        <td class="label-cell">Fecha de Recepción:</td>
        <td class="content-cell">{{ $registro->fecha_recepcion_format }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Tipo de Recepción:</td>
        <td class="content-cell">{{ $registro->tipo_recepcion }}</td>
        <td class="label-cell">Estado del Proceso:</td>
        <td class="content-cell">{{ $registro->estado_proceso }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Proyecto Asociado:</td>
        <td colspan="3" class="content-cell">{{ $registro->proyecto?->codigo_proyecto }} - {{ $registro->proyecto?->nombre_proyecto }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Remitente:</td>
        <td class="content-cell">{{ $registro->remitente }}</td>
        <td class="label-cell">Procedencia:</td>
        <td class="content-cell">{{ $registro->procedencia }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Documento de Referencia:</td>
        <td class="content-cell">{{ $registro->documento_referencia }}</td>
        <td class="label-cell">Responsable:</td>
        <td class="content-cell">{{ $registro->responsable?->nombre_completo }}</td>
    </tr>
</table>

<!-- II. DESCRIPCIÓN DEL MATERIAL -->
<table>
    <tr>
        <td colspan="4" class="section-header">II. DESCRIPCIÓN DEL MATERIAL</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Descripción del Material:</td>
        <td colspan="3" class="content-cell text-area">{{ $registro->descripcion_material }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Tipo de Material:</td>
        <td class="content-cell">{{ $registro->tipoMaterial?->nombre }}</td>
        <td class="label-cell">Cantidad:</td>
        <td class="content-cell">{{ $registro->cantidad }} {{ $registro->unidad_medida }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Peso (kg):</td>
        <td class="content-cell">{{ $registro->peso_kg }}</td>
        <td class="label-cell">Dimensiones:</td>
        <td class="content-cell">{{ $registro->dimensiones }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Estado de Conservación al Ingreso:</td>
        <td colspan="3" class="content-cell">{{ $registro->estado_conservacion_ingreso }}</td>
    </tr>
</table>

<!-- III. CONDICIONES DE RECEPCIÓN -->
<table>
    <tr>
        <td colspan="4" class="section-header">III. CONDICIONES DE RECEPCIÓN</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Embalaje:</td>
        <td class="content-cell">{{ $registro->embalaje }}</td>
        <td class="label-cell">Documentación Completa:</td>
        <td class="content-cell">{{ $registro->documentacion_completa ? 'Sí' : 'No' }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Registro Fotográfico:</td>
        <td class="content-cell">{{ $registro->registro_fotografico ? 'Sí' : 'No' }}</td>
        <td class="label-cell">Total Imágenes:</td>
        <td class="content-cell">{{ $registro->imagenes?->count() ?? 0 }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Documentos Adjuntos:</td>
        <td colspan="3" class="content-cell">{{ $registro->documentos_adjuntos }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Observaciones de Ingreso:</td>
        <td colspan="3" class="content-cell text-area">{{ $registro->observaciones_ingreso }}</td>
    </tr>
</table>

<!-- IV. UBICACIÓN Y ALMACENAMIENTO -->
<table>
    <tr>
        <td colspan="4" class="section-header">IV. UBICACIÓN Y ALMACENAMIENTO</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Ubicación Temporal:</td>
        <td class="content-cell">{{ $registro->ubicacion_temporal }}</td>
        <td class="label-cell">Área de Almacenamiento:</td>
        <td class="content-cell">{{ $registro->area_almacenamiento }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Condiciones Ambientales:</td>
        <td colspan="3" class="content-cell">{{ $registro->condiciones_ambientales }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Requiere Tratamiento Urgente:</td>
        <td class="content-cell">{{ $registro->requiere_tratamiento_urgente ? 'Sí' : 'No' }}</td>
        <td class="label-cell" colspan="2"></td>
    </tr>
    @if($registro->requiere_tratamiento_urgente)
    <tr class="info-row">
        <td class="label-cell">Tratamiento Requerido:</td>
        <td colspan="3" class="content-cell text-area">{{ $registro->tratamiento_requerido }}</td>
    </tr>
    @endif
</table>

<!-- V. VALORACIÓN Y CLASIFICACIÓN -->
<table>
    <tr>
        <td colspan="4" class="section-header">V. VALORACIÓN Y CLASIFICACIÓN</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Valor Estimado:</td>
        <td class="content-cell">{{ $registro->valor_estimado ? 'S/ ' . number_format($registro->valor_estimado, 2) : '-' }}</td>
        <td class="label-cell">Clasificación:</td>
        <td class="content-cell">{{ $registro->clasificacion }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Período Cronológico:</td>
        <td class="content-cell">{{ $registro->periodo_cronologico }}</td>
        <td class="label-cell">Cultura:</td>
        <td class="content-cell">{{ $registro->cultura }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Patrimonio Cultural:</td>
        <td colspan="3" class="content-cell">{{ $registro->patrimonio_cultural ? 'Sí' : 'No' }}</td>
    </tr>
</table>

<!-- VI. SEGUIMIENTO -->
<table>
    <tr>
        <td colspan="2" class="section-header">VI. SEGUIMIENTO</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Acciones Realizadas:</td>
        <td class="content-cell text-area">{{ $registro->acciones_realizadas }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha de Revisión:</td>
        <td class="content-cell">{{ $registro->fecha_revision_format }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Revisado por:</td>
        <td class="content-cell">{{ $registro->revisado_por }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Observaciones Finales:</td>
        <td class="content-cell text-area">{{ $registro->observaciones_finales }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Recomendaciones:</td>
        <td class="content-cell text-area">{{ $registro->recomendaciones }}</td>
    </tr>
</table>

<!-- Documentos Adjuntos -->
@if($registro->documentos && $registro->documentos->count() > 0)
<table>
    <tr>
        <td colspan="3" class="section-header">DOCUMENTOS ADJUNTOS</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">Nombre Documento</th>
        <th style="background-color: #f0f0f0;">Tipo</th>
        <th style="background-color: #f0f0f0;">Archivo</th>
    </tr>
    @foreach($registro->documentos as $doc)
    <tr>
        <td>{{ $doc->nombre_documento }}</td>
        <td>{{ $doc->tipo_documento }}</td>
        <td>{{ $doc->archivo }}</td>
    </tr>
    @endforeach
</table>
@endif

<!-- Footer con información de auditoría -->
<table style="margin-top: 30px;">
    <tr>
        <td colspan="2" style="background-color: #f0f0f0; font-weight: bold; text-align: center;">INFORMACIÓN DE AUDITORÍA</td>
    </tr>
    <tr>
        <td class="label-cell">Creado por:</td>
        <td>{{ $registro->creadoPor?->name }} - {{ $registro->fecha_creacion_format }}</td>
    </tr>
    @if($registro->modificadoPor)
    <tr>
        <td class="label-cell">Modificado por:</td>
        <td>{{ $registro->modificadoPor?->name }} - {{ $registro->fecha_modificacion_format }}</td>
    </tr>
    @endif
</table>

</body>
</html>

