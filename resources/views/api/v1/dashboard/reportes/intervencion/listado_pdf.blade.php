<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Intervenciones</title>
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
            background-color: #4472C4;
            color: white;
        }
    </style>
</head>
<body>
<div class="titulo">Reporte de Intervenciones</div>
<table>
    <thead>
    <tr>
        <th style="text-align: center">N° Ficha</th>
        <th style="text-align: center">N° Inventario</th>
        <th style="text-align: center">Código Museo</th>
        <th style="text-align: center">Fecha Recepción</th>
        <th style="text-align: center">Fecha Entrega</th>
        <th style="text-align: center">Categoría</th>
        <th style="text-align: center">Denominación</th>
        <th style="text-align: center">Material</th>
        <th style="text-align: center">Cultura/Estilo</th>
        <th style="text-align: center">Integridad</th>
        <th style="text-align: center">Estado</th>
        <th style="text-align: center">Director/Encargado</th>
        <th style="text-align: center">Conservador</th>
    </tr>
    </thead>
    <tbody>
    @foreach($intervenciones as $i)
        <tr>
            <td>{{ $i->numero_ficha }}</td>
            <td>{{ $i->numero_inventario }}</td>
            <td>{{ $i->codigo_museo }}</td>
            <td>{{ $i->fecha_recepcion_format }}</td>
            <td>{{ $i->fecha_entrega_format }}</td>
            <td>{{ $i->categoria?->nombre }}</td>
            <td>{{ $i->denominacion?->nombre }}</td>
            <td>{{ $i->material }}</td>
            <td>{{ $i->cultura_estilo_autor }}</td>
            <td>{{ is_array($i->integridad) ? implode(', ', $i->integridad) : $i->integridad }}</td>
            <td>{{ is_array($i->tipo_dano) ? implode(', ', $i->tipo_dano) : $i->tipo_dano }}</td>
            <td>{{ $i->directorEncargado?->nombre_completo }}</td>
            <td>{{ $i->conservadorResponsable?->nombre_completo }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
