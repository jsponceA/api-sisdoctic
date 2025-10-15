<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estados de Conservación</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
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
            padding: 8px;
            text-align: left;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="titulo">Reporte de Estados de Conservación</div>
<table>
    <thead>
    <tr>
        <th>Nombre</th>
    </tr>
    </thead>
    <tbody>
    @foreach($estadoConservaciones as $ec)
        <tr>
            <td>{{ $ec->nombre }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
