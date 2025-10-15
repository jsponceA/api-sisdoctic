<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">N° Recepción</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Recepción</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Tipo Recepción</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Proyecto</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Remitente</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Procedencia</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Descripción Material</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Cantidad</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Unidad Medida</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Estado Conservación</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Estado Proceso</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Responsable</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Ubicación Temporal</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Clasificación</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Valor Estimado</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($registros as $r)
        <tr>
            <td style="border: 1px solid #000;">{{ $r->numero_recepcion }}</td>
            <td style="border: 1px solid #000;">{{ $r->fecha_recepcion_format }}</td>
            <td style="border: 1px solid #000;">{{ $r->tipo_recepcion }}</td>
            <td style="border: 1px solid #000;">{{ $r->proyecto?->nombre_proyecto }}</td>
            <td style="border: 1px solid #000;">{{ $r->remitente }}</td>
            <td style="border: 1px solid #000;">{{ $r->procedencia }}</td>
            <td style="border: 1px solid #000;">{{ $r->descripcion_material }}</td>
            <td style="border: 1px solid #000;">{{ $r->cantidad }}</td>
            <td style="border: 1px solid #000;">{{ $r->unidad_medida }}</td>
            <td style="border: 1px solid #000;">{{ $r->estado_conservacion_ingreso }}</td>
            <td style="border: 1px solid #000;">{{ $r->estado_proceso }}</td>
            <td style="border: 1px solid #000;">{{ $r->responsable?->nombre_completo }}</td>
            <td style="border: 1px solid #000;">{{ $r->ubicacion_temporal }}</td>
            <td style="border: 1px solid #000;">{{ $r->clasificacion }}</td>
            <td style="border: 1px solid #000;">{{ $r->valor_estimado ? 'S/ ' . number_format($r->valor_estimado, 2) : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Registros de Recepción</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7px;
            color: #333;
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #2c61ed;
            color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 6px;
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
    </style>
</head>
<body>
<div class="titulo">Reporte de Registros de Recepción</div>
<table>
    <thead>
    <tr>
        <th style="text-align: center">N° Recepción</th>
        <th style="text-align: center">Fecha</th>
        <th style="text-align: center">Tipo</th>
        <th style="text-align: center">Proyecto</th>
        <th style="text-align: center">Remitente</th>
        <th style="text-align: center">Procedencia</th>
        <th style="text-align: center">Descripción Material</th>
        <th style="text-align: center">Cantidad</th>
        <th style="text-align: center">Estado Proceso</th>
        <th style="text-align: center">Responsable</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $r)
        <tr>
            <td>{{ $r->numero_recepcion }}</td>
            <td>{{ $r->fecha_recepcion_format }}</td>
            <td>{{ $r->tipo_recepcion }}</td>
            <td>{{ $r->proyecto?->nombre_proyecto }}</td>
            <td>{{ $r->remitente }}</td>
            <td>{{ $r->procedencia }}</td>
            <td>{{ Str::limit($r->descripcion_material, 50) }}</td>
            <td>{{ $r->cantidad }} {{ $r->unidad_medida }}</td>
            <td>{{ $r->estado_proceso }}</td>
            <td>{{ $r->responsable?->nombre_completo }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

