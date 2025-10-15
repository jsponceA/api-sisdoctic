<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Artículos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #333;
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 7px;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }

        th {
            font-weight: bold;
        }

        .truncate {
            max-width: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="titulo">Reporte de Artículos</div>
<table>
    <thead>
    <tr>
        <th ></th>
        <th style="background-color: #BF8F00;text-align: center" colspan="5">DATOS DEL BIEN CULTURAL</th>
        <th style="background-color: #F4B084;text-align: center" colspan="2">UBICACIÓN</th>
        <th ></th>
        <th style="background-color: #F4B084;text-align: center" colspan="2">ESTADO DE INTEGRIDAD</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th style="background-color: #70AD47; color: black;text-align: center">N° de ficha</th>
        <th style="background-color: #BF8F00; color: black;text-align: center">Código inventario de objeto</th>
        <th style="background-color: #BF8F00; color: black;text-align: center">Nº Registro Nacional</th>
        <th style="background-color: #BF8F00; color: black;text-align: center">Tipo / Material</th>
        <th style="background-color: #BF8F00; color: black;text-align: center">Denominación</th>
        <th style="background-color: #BF8F00; color: black;text-align: center">Procedencia / Sitio</th>
        <th style="background-color: #F4B084; color: black;text-align: center">Sala</th>
        <th style="background-color: #F4B084; color: black;text-align: center">N° de vitrina</th>
        <th style="background-color: #D0CECE; color: black;text-align: center">Descripción del objeto</th>
        <th style="background-color: #F4B084; color: black;text-align: center">Completo</th>
        <th style="background-color: #F4B084; color: black;text-align: center">Incompleto</th>
        <th style="background-color: #6666FF; color: black;text-align: center">Porcentaje de integridad</th>
        <th style="background-color: #FFE699; color: black;text-align: center">Estado de conservación</th>
        <th style="background-color: #BDD7EE; color: black;text-align: center">Diagnóstico general</th>
        <th style="background-color: #CBADCB; color: black;text-align: center">Proceso del trabajo</th>
        <th style="background-color: #B62245; color: black;text-align: center">Identificación de fotos</th>
        <th style="background-color: white; color: black;text-align: center">Responsables</th>
        <th style="background-color: #FF99FF; color: black;text-align: center">Observaciones</th>
        <th style="background-color: #27A1B1; color: black;text-align: center">Fecha Inicio</th>
        <th style="background-color: #27A1B1; color: black;text-align: center">Fecha Fin</th>
    </tr>
    </thead>
    <tbody>
    @foreach($articulos as $a)
        <tr>
            <th >{{$a->numero_ficha}}</th>
            <td >{{ $a->codigo_inventario_objeto }}</td>
            <td >{{ $a->numero_registro_nacional }}</td>
            <td >{{ $a->tipoMaterial?->nombre }}</td>
            <td >{{ $a->denominacion?->nombre }}</td>
            <td >{{ $a->precedencia_sitio }}</td>
            <td >{{ $a->sala?->nombre }}</td>
            <td >{{ $a->numero_vitrina }}</td>
            <td >{{ $a->descripcion_objeto }}</td>
            <td >{{ $a->estado_integridad ? 'X' : '' }}</td>
            <td >{{ !$a->estado_integridad ? 'X' : '' }}</td>
            <td >{{ $a->porcentaje_integridad }}%</td>
            <td >{{ $a->estadoConservacion?->nombre }}</td>
            <td >{{ $a->diagnostico_general }}</td>
            <td >{!! strlen($a->proceso_trabajo) > 80 ? wordwrap($a->proceso_trabajo, 80, '<br>', true) : $a->proceso_trabajo !!}</td>
            <td >{{ $a->indentificacion_fotos }}</td>
            <td >{{ $a->responsables?->map(fn($resp)=>$resp->nombre_completo)->join("\n") }}</td>
            <td >{{ $a->observaciones }}</td>
            <td >{{ $a->fecha_inicio_format }}</td>
            <td >{{ $a->fecha_fin_format }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
