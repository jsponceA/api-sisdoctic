<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Nombres</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Apellidos</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Correo</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Tipo Documento</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Número Documento</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($responsables as $r)
        <tr>
            <td style="border: 1px solid #000;">{{ $r->nombres }}</td>
            <td style="border: 1px solid #000;">{{ $r->apellidos }}</td>
            <td style="border: 1px solid #000;">{{ $r->correo ?? 'N/A' }}</td>
            <td style="border: 1px solid #000;">{{ $r->tipo_documento ?? 'N/A' }}</td>
            <td style="border: 1px solid #000;">{{ $r->numero_documento ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

