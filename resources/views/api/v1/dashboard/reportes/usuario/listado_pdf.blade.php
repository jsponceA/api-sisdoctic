<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de usuarios</title>
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
<div class="titulo">Reporte de Usuarios</div>
<table>
    <thead>
    <tr>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Nombre y apellidos</th>
        <th>Correo</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->username }}</td>
            <td >{{ $usuario->roles?->first()?->name }}</td>
            <td>{{ $usuario->nombres_apellidos }}</td>
            <td >{{ $usuario->correo }}</td>
            @if($usuario->estado)
                <td style="background-color: #14a514;color: black">Habilitado</td>
            @else
                <td style="background-color: #e8af3b;color: black">Deshabilitado</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
