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
    @foreach ($tipos_documento_clasificacion as $tipo)
        <tr>
            <td style="border: 1px solid #000;">{{ $tipo->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $tipo->descripcion ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

