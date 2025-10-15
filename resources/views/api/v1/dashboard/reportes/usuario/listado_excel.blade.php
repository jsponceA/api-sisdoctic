<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Usuario</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Rol</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Nombre y apellidos</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Correo</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($usuarios as $usuario)
        <tr>
            <td style="border: 1px solid #000;">{{ $usuario->username }}</td>
            <td style="border: 1px solid #000;">{{ $usuario->roles?->first()?->name }}</td>
            <td style="border: 1px solid #000;">{{ $usuario->nombres_apellidos }}</td>
            <td style="border: 1px solid #000;">{{ $usuario->correo }}</td>
            @if($usuario->estado)
                <td style="border: 1px solid #000;background-color: #14a514;color: black">Habilitado</td>
            @else
                <td style="border: 1px solid #000;background-color: #e8af3b;color: black">Deshabilitado</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
