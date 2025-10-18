<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Proyectos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
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
            padding: 5px;
            text-align: left;
            font-size: 8px;
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
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="titulo">Reporte de Proyectos</div>
<table>
    <thead>
    <tr>
        <th style="text-align: center">Código</th>
        <th style="text-align: center">Nombre Proyecto</th>
        <th style="text-align: center">Responsables</th>
        <th style="text-align: center">Fecha Inicio</th>
        <th style="text-align: center">Fecha Fin</th>
        <th style="text-align: center">Descripción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($proyectos as $p)
        <tr>
            <td>{{ $p->codigo_proyecto }}</td>
            <td>{{ $p->nombre }}</td>
            <td>
                @if($p->responsables && $p->responsables->count() > 0)
                    @foreach($p->responsables as $resp)
                        {{ $resp->responsable?->nombre_completo }}@if($resp->especialidad) ({{ $resp->especialidad?->nombre }})@endif
                        @if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
            <td>{{ $p->fecha_inicio_format }}</td>
            <td>{{ $p->fecha_fin_format }}</td>
            <td class="truncate">{{ $p->descripcion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

