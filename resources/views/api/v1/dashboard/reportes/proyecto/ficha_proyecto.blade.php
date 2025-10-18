<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Proyecto</title>
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
    </style>
</head>
<body>
{{--<header>
    <img src="{{public_path('img/logo_mincul.jpg')}}" style="width: 377px;height: 45px">
    <img src="{{public_path('img/logo_puruchuco.png')}}" style="width: 111px;height: 60px;float: right">
</header>--}}

<div class="titulo-principal">FICHA DE PROYECTO</div>

<!-- I. INFORMACIÓN BÁSICA DEL PROYECTO -->
<table>
    <tr>
        <td colspan="4" class="section-header">I. INFORMACIÓN BÁSICA DEL PROYECTO</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Código de Proyecto:</td>
        <td colspan="3" class="content-cell">{{ $proyecto->codigo_proyecto }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Nombre del Proyecto:</td>
        <td colspan="3" class="content-cell">{{ $proyecto->nombre }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha de Inicio:</td>
        <td class="content-cell">{{ $proyecto->fecha_inicio_format }}</td>
        <td class="label-cell">Fecha de Fin:</td>
        <td class="content-cell">{{ $proyecto->fecha_fin_format }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Descripción:</td>
        <td colspan="3" class="content-cell text-area">{{ $proyecto->descripcion }}</td>
    </tr>
</table>

<!-- II. RESPONSABLES DEL PROYECTO -->
@if($proyecto->responsables && $proyecto->responsables->count() > 0)
<table>
    <tr>
        <td colspan="4" class="section-header">II. RESPONSABLES DEL PROYECTO</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">Responsable</th>
        <th style="background-color: #f0f0f0;">Teléfono</th>
        <th style="background-color: #f0f0f0;">Correo</th>
        <th style="background-color: #f0f0f0;">Especialidad</th>
    </tr>
    @foreach($proyecto->responsables as $resp)
    <tr>
        <td>{{ $resp->responsable?->nombre_completo }}</td>
        <td>{{ $resp->responsable?->telefono ?? '-' }}</td>
        <td>{{ $resp->responsable?->correo ?? '-' }}</td>
        <td>{{ $resp->especialidad?->nombre ?? '-' }}</td>
    </tr>
    @endforeach
</table>
@endif

<!-- III. TIPOS DE DOCUMENTO ASOCIADOS -->
@if($proyecto->tiposDocumento && $proyecto->tiposDocumento->count() > 0)
<table>
    <tr>
        <td colspan="3" class="section-header">III. TIPOS DE DOCUMENTO ASOCIADOS</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">Tipo de Documento</th>
        <th style="background-color: #f0f0f0;">Días de Plazo</th>
        <th style="background-color: #f0f0f0;">Penalidad</th>
    </tr>
    @foreach($proyecto->tiposDocumento as $td)
    <tr>
        <td>{{ $td->tipoDocumento?->nombre }}</td>
        <td>{{ $td->dias_plazo ?? '-' }} días</td>
        <td>{{ $td->penalidad ? 'S/ ' . number_format($td->penalidad, 2) : '-' }}</td>
    </tr>
    @endforeach
</table>
@endif

<!-- IV. DOCUMENTOS ADJUNTOS -->
@if($proyecto->documentos && $proyecto->documentos->count() > 0)
<table>
    <tr>
        <td colspan="2" class="section-header">IV. DOCUMENTOS ADJUNTOS</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">#</th>
        <th style="background-color: #f0f0f0;">Archivo</th>
    </tr>
    @foreach($proyecto->documentos as $index => $doc)
    <tr>
        <td style="text-align: center;">{{ $index + 1 }}</td>
        <td>{{ $doc->archivo_url }}</td>
    </tr>
    @endforeach
</table>
@endif

<!-- V. FOTOGRAFÍAS -->
@if($proyecto->fotografias && $proyecto->fotografias->count() > 0)
<table>
    <tr>
        <td colspan="2" class="section-header">V. FOTOGRAFÍAS DEL PROYECTO</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">#</th>
        <th style="background-color: #f0f0f0;">Archivo</th>
    </tr>
    @foreach($proyecto->fotografias as $index => $img)
    <tr>
        <td style="text-align: center;">{{ $index + 1 }}</td>
        <td>{{ $img->foto_url }}</td>
    </tr>
    @endforeach
</table>
@endif



</body>
</html>
