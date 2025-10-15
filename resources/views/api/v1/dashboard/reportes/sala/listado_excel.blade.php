<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Nombre</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($salas as $s)
        <tr>
            <td style="border: 1px solid #000;">{{ $s->nombre }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

