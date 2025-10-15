<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Código</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Nombre Proyecto</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Categoría</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Responsable</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Ubicación</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Estado</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Inicio</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Fin</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Presupuesto</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fuente Financiamiento</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">% Avance</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">N° Beneficiarios</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Descripción</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Objetivos</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($proyectos as $p)
        <tr>
            <td style="border: 1px solid #000;">{{ $p->codigo_proyecto }}</td>
            <td style="border: 1px solid #000;">{{ $p->nombre_proyecto }}</td>
            <td style="border: 1px solid #000;">{{ $p->categoria?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $p->responsable?->nombre_completo }}</td>
            <td style="border: 1px solid #000;">{{ $p->ubicacion }}</td>
            <td style="border: 1px solid #000;">{{ $p->estado }}</td>
            <td style="border: 1px solid #000;">{{ $p->fecha_inicio_format }}</td>
            <td style="border: 1px solid #000;">{{ $p->fecha_fin_format }}</td>
            <td style="border: 1px solid #000;">{{ $p->presupuesto ? 'S/ ' . number_format($p->presupuesto, 2) : '-' }}</td>
            <td style="border: 1px solid #000;">{{ $p->fuente_financiamiento }}</td>
            <td style="border: 1px solid #000;">{{ $p->porcentaje_avance }}%</td>
            <td style="border: 1px solid #000;">{{ $p->numero_beneficiarios }}</td>
            <td style="border: 1px solid #000;">{{ $p->descripcion }}</td>
            <td style="border: 1px solid #000;">{{ $p->objetivos }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Proyectos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #333;
            margin: 10px;
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
            padding: 4px;
            text-align: left;
            font-size: 7px;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }

        th {
            font-weight: bold;
        }

        .truncate {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="titulo">Reporte de Proyectos</div>
<table>
    <thead>
    <tr>
        <th style="text-align: center">Código</th>
        <th style="text-align: center">Nombre Proyecto</th>
        <th style="text-align: center">Categoría</th>
        <th style="text-align: center">Responsable</th>
        <th style="text-align: center">Ubicación</th>
        <th style="text-align: center">Estado</th>
        <th style="text-align: center">Fecha Inicio</th>
        <th style="text-align: center">Fecha Fin</th>
        <th style="text-align: center">Presupuesto</th>
        <th style="text-align: center">% Avance</th>
        <th style="text-align: center">N° Beneficiarios</th>
    </tr>
    </thead>
    <tbody>
    @foreach($proyectos as $p)
        <tr>
            <td>{{ $p->codigo_proyecto }}</td>
            <td>{{ $p->nombre_proyecto }}</td>
            <td>{{ $p->categoria?->nombre }}</td>
            <td>{{ $p->responsable?->nombre_completo }}</td>
            <td>{{ $p->ubicacion }}</td>
            <td>{{ $p->estado }}</td>
            <td>{{ $p->fecha_inicio_format }}</td>
            <td>{{ $p->fecha_fin_format }}</td>
            <td>{{ $p->presupuesto ? 'S/ ' . number_format($p->presupuesto, 2) : '-' }}</td>
            <td>{{ $p->porcentaje_avance }}%</td>
            <td>{{ $p->numero_beneficiarios }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

