<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Registros de Recepción</title>
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
            background-color: #2c61ed;
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

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6px;
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
<div class="titulo">Reporte de Registros de Recepción</div>
<table>
    <thead>
    <tr>
        <th style="text-align: center">Proyecto</th>
        <th style="text-align: center">N° Doc Recep</th>
        <th style="text-align: center">Asunto</th>
        <th style="text-align: center">F. Recepción</th>
        <th style="text-align: center">Tipo Doc</th>
        <th style="text-align: center">Especialidad</th>
        <th style="text-align: center">Destino</th>
        <th style="text-align: center">Situación</th>
        <th style="text-align: center">Prioridad</th>
        <th style="text-align: center">F. Respuesta</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $r)
        <tr>
            <td>{{ $r->proyecto?->nombre }}</td>
            <td>{{ $r->num_doc_recep }}</td>
            <td>{{ \Illuminate\Support\Str::limit($r->asunto, 40) }}</td>
            <td>{{ $r->fecha_recepcion_format }}</td>
            <td>{{ $r->tipoDocumento?->nombre }}</td>
            <td>{{ $r->especialidad?->nombre }}</td>
            <td>{{ $r->destino }}</td>
            <td>
                @if($r->situacion == 'R')
                    <span class="badge badge-success">Respondido</span>
                @else
                    <span class="badge badge-danger">Sin Responder</span>
                @endif
            </td>
            <td style="text-align: center">{{ $r->prioridad ?? '-' }}</td>
            <td>{{ $r->fecha_respuesta_format ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

