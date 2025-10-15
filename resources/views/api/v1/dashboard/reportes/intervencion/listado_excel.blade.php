<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">N° Ficha</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">N° Inventario</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Código Museo</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Recepción</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Entrega</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Categoría</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Denominación</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Material</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Cultura/Estilo</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Integridad</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Estado</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Director/Encargado</th>
        <th style="background-color: #4472C4; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Conservador</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($intervenciones as $i)
        <tr>
            <td style="border: 1px solid #000;">{{ $i->numero_ficha }}</td>
            <td style="border: 1px solid #000;">{{ $i->numero_inventario }}</td>
            <td style="border: 1px solid #000;">{{ $i->codigo_museo }}</td>
            <td style="border: 1px solid #000;">{{ $i->fecha_recepcion_format }}</td>
            <td style="border: 1px solid #000;">{{ $i->fecha_entrega_format }}</td>
            <td style="border: 1px solid #000;">{{ $i->categoria?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $i->denominacion?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $i->material }}</td>
            <td style="border: 1px solid #000;">{{ $i->cultura_estilo_autor }}</td>
            <td style="border: 1px solid #000;">{{ is_array($i->integridad) ? implode(', ', $i->integridad) : $i->integridad }}</td>
            <td style="border: 1px solid #000;">{{ is_array($i->tipo_dano) ? implode(', ', $i->tipo_dano) : $i->tipo_dano }}</td>
            <td style="border: 1px solid #000;">{{ $i->directorEncargado?->nombre_completo }}</td>
            <td style="border: 1px solid #000;">{{ $i->conservadorResponsable?->nombre_completo }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
