<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de roles</title>
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
<div class="titulo">Reporte de Roles</div>
<table>
    <thead>
    <tr>
        <th>Nombre del rol</th>
    </tr>
    </thead>
    <tbody>
    @foreach($roles as $rol)
        <tr>
            <td>{{ $rol->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
