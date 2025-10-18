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
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Descripción</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($areas as $area)
        <tr>
            <td style="border: 1px solid #000;">{{ $area->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $area->descripcion ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>


