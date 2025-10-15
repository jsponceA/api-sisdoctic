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
    @foreach ($categorias as $c)
        <tr>
            <td style="border: 1px solid #000;">{{ $c->nombre }}</td>
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
    <title>Reporte de categorías</title>
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
<div class="titulo">Reporte de Categorías</div>
<table>
    <thead>
    <tr>
        <th>Nombre</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categorias as $c)
        <tr>
            <td>{{ $c->nombre }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>

