<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha de Conservación Preventiva</title>
    <style>
        @page {

            margin: 80px 25px;

        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
        }

        .titulo-principal {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        .container-top {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .left-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .right-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }

        .ficha-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .ficha-table td:first-child {
            width: 35%;
            font-weight: bold;
            text-align: center;
            font-size: 11pt;
        }

        .fecha-table {
            width: 100%;
            border-collapse: collapse;
        }

        .fecha-table th {
            background-color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }

        .fecha-table td {
            text-align: center;
            padding: 8px 5px;
        }

        .datos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .datos-table th {
            background-color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 6px;
        }

        .datos-table td:first-child {
            width: 45%;
            font-weight: normal;
            padding: 6px 8px;
        }

        .section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            padding: 8px;
            background-color: #fff;
        }

        .section-content {
            height: 110px;
            padding: 2px;
        }

        .responsables-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .responsables-title {
            font-weight: bold;
            padding: 8px;
        }

        .responsables-content {
            height: 50px;
            padding: 2px;
        }

        .foto-table {
            width: 100%;
            border-collapse: collapse;
        }

        .foto-table th {
            background-color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }

        .foto-cell {
            height: 240px;
            width: 50%;
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }

        .page-break {
            page-break-after: always;
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
    </style>
</head>
<body>
<header>
    <img src="{{public_path('img/logo_mincul.jpg')}}" style="width: 377px;height: 45px">
    <img src="{{public_path('img/logo_puruchuco.png')}}" style="width: 111px;height: 60px;float: right">
</header>
<div style="clear: both"></div>
<div class="titulo-principal">FICHA DE CONSERVACIÓN PREVENTIVA</div>

<!-- Top Section Container -->
<div class="container-top">
    <div class="left-section">
        <!-- Ficha N° -->
        <table class="ficha-table">
            <tr>
                <td>FICHA N°</td>
                <td>{{$articulo->numero_ficha}}</td>
            </tr>
        </table>

        <!-- Fecha -->
        <table class="fecha-table">
            <tr>
                <th colspan="4">FECHA</th>
            </tr>
            <tr>
                <td style="width: 15%;">Inicio</td>
                <td style="width: 20%;">
                    @if(!empty($articulo->fecha_inicio))
                        {{now()->parse($articulo->fecha_inicio)->monthName}}<br>{{now()->parse($articulo->fecha_inicio)->year}}
                    @endif
                </td>
                <td style="width: 25%;">Culminación:</td>
                <td style="width: 40%;">
                    @if(!empty($articulo->fecha_fin))
                        {{now()->parse($articulo->fecha_fin)->monthName}}<br>{{now()->parse($articulo->fecha_fin)->year}}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="right-section">
        <!-- Datos del Bien Cultural -->
        <table class="datos-table">
            <tr>
                <th colspan="2">DATOS DEL BIEN CULTURAL</th>
            </tr>
            <tr>
                <td>Código<br>inventario de<br>objeto</td>
                <td>{{$articulo->codigo_inventario_objeto}}</td>
            </tr>
            <tr>
                <td>Nº Registro<br>Nacional</td>
                <td>{{$articulo->numero_registro_nacional}}</td>
            </tr>
            <tr>
                <td>Tipo /<br>Material</td>
                <td>{{$articulo->tipoMaterial?->nombre}}</td>
            </tr>
            <tr>
                <td>Denominación</td>
                <td>{{$articulo->denominacion?->nombre}}</td>
            </tr>
        </table>
    </div>
</div>

<!-- Diagnóstico General -->
<table class="section-table">
    <tr>
        <td class="section-title">DIAGNÓSTICO GENERAL</td>
    </tr>
    <tr>
        <td class="section-content">
            {{$articulo->diagnostico_general}}
        </td>
    </tr>
</table>

<!-- Proceso de Trabajo -->
<table class="section-table">
    <tr>
        <td class="section-title">PROCESO DE TRABAJO</td>
    </tr>
    <tr>
        <td class="section-content">
            {{$articulo->proceso_trabajo}}
        </td>
    </tr>
</table>

<!-- Responsables -->
<table class="responsables-table">
    <tr>
        <td class="responsables-title">RESPONSABLES:</td>
    </tr>
    <tr>
        <td class="responsables-content">
            {!! $articulo->responsables?->map(fn($responsable) => $responsable->nombre_completo)->join('<br>') !!}
        </td>
    </tr>
</table>

<div class="page-break"></div>


<!-- Registro Fotográfico -->
<table class="foto-table">
    <tr>
        <th colspan="2">REGISTRO FOTOGRÁFICO</th>
    </tr>
    <tr>
        <th style="width: 50%;">Vista inicial</th>
        <th style="width: 50%;">Vista Final</th>
    </tr>
    <tr>
        <td class="foto-cell">
            @if(!empty($articulo->foto_registro_inicial))
                <img src="{{public_path('storage/dashboard/articulos/'.$articulo->foto_registro_inicial)}}" style="max-width: 100%; max-height: 100%;">
            @endif
        </td>
        <td class="foto-cell">
            @if(!empty($articulo->foto_registro_final))
                <img src="{{public_path('storage/dashboard/articulos/'.$articulo->foto_registro_final)}}" style="max-width: 100%; max-height: 100%;">
            @endif
        </td>
    </tr>
</table>

<!-- Del proceso de trabajo -->
<table class="foto-table">
    <tr>
        <th colspan="2">REGISTRO DEL PROCESO DE TRABAJO</th>
    </tr>
    <tr>
        <th style="width: 50%;">Vista inicial</th>
        <th style="width: 50%;">Vista Final</th>
    </tr>
    <tr>
        <td class="foto-cell">
            @if(!empty($articulo->foto_proceso_inicial))
                <img src="{{public_path('storage/dashboard/articulos/'.$articulo->foto_proceso_inicial)}}" style="max-width: 100%; max-height: 100%;">
            @endif
        </td>
        <td class="foto-cell">
            @if(!empty($articulo->foto_proceso_final))
                <img src="{{public_path('storage/dashboard/articulos/'.$articulo->foto_proceso_final)}}" style="max-width: 100%; max-height: 100%;">
            @endif
        </td>
    </tr>
</table>

</body>
</html>
