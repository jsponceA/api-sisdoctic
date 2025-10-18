<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Responsables</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
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
            padding: 6px;
            text-align: left;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        th {
            font-size: 9px;
        }
    </style>
</head>
<body>
<div class="titulo">Reporte de Responsables</div>
<table>
    <thead>
    <tr>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Tipo Doc.</th>
        <th>Núm. Doc.</th>
    </tr>
    </thead>
    <tbody>
    @foreach($responsables as $r)
        <tr>
            <td>{{ $r->nombres }}</td>
            <td>{{ $r->apellidos }}</td>
            <td>{{ $r->correo ?? 'N/A' }}</td>
            <td>{{ $r->telefono ?? 'N/A' }}</td>
            <td>{{ $r->tipo_documento ?? 'N/A' }}</td>
            <td>{{ $r->numero_documento ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
