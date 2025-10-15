<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="border: 1px solid #000;"></th>
        <th style="background-color: #BF8F00; color: black; font-weight: bold;border: 1px solid #000;text-align: center" colspan="5">DATOS DEL BIEN CULTURAL</th>
        <th style="background-color: #F4B084; color: black; font-weight: bold;border: 1px solid #000;text-align: center" colspan="2">UBICACIÓN</th>
        <th style="border: 1px solid #000;"></th>
        <th style="background-color: #F4B084; color: black; font-weight: bold;border: 1px solid #000;text-align: center" colspan="2">ESTADO DE INTEGRIDAD</th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
        <th style="border: 1px solid #000;"></th>
    </tr>
    <tr>
        <th style="background-color: #70AD47; color: black; font-weight: bold;border: 1px solid #000;text-align: center">N° de ficha</th>
        <th style="background-color: #BF8F00; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Código inventario de objeto</th>
        <th style="background-color: #BF8F00; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Nº Registro Nacional</th>
        <th style="background-color: #BF8F00; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Tipo / Material</th>
        <th style="background-color: #BF8F00; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Denominación</th>
        <th style="background-color: #BF8F00; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Procedencia / Sitio</th>
        <th style="background-color: #F4B084; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Sala</th>
        <th style="background-color: #F4B084; color: black; font-weight: bold;border: 1px solid #000;text-align: center">N° de vitrina</th>
        <th style="background-color: #D0CECE; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Descripción del objeto</th>
        <th style="background-color: #F4B084; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Completo</th>
        <th style="background-color: #F4B084; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Incompleto</th>
        <th style="background-color: #6666FF; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Porcentaje de integridad</th>
        <th style="background-color: #FFE699; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Estado de conservación</th>
        <th style="background-color: #BDD7EE; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Diagnóstico general</th>
        <th style="background-color: #CBADCB; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Proceso del trabajo</th>
        <th style="background-color: #B62245; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Identificación de fotos</th>
        <th style="background-color: white; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Responsables</th>
        <th style="background-color: #FF99FF; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Observaciones</th>
        <th style="background-color: #27A1B1; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Fecha Inicio</th>
        <th style="background-color: #27A1B1; color: black; font-weight: bold;border: 1px solid #000;text-align: center">Fecha Fin</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($articulos as $a)
        <tr>
            <th style="border: 1px solid #000;">{{$a->numero_ficha}}</th>
            <td style="border: 1px solid #000;">{{ $a->codigo_inventario_objeto }}</td>
            <td style="border: 1px solid #000;">{{ $a->numero_registro_nacional }}</td>
            <td style="border: 1px solid #000;">{{ $a->tipoMaterial?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $a->denominacion?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $a->precedencia_sitio }}</td>
            <td style="border: 1px solid #000;">{{ $a->sala?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $a->numero_vitrina }}</td>
            <td style="border: 1px solid #000;">{{ $a->descripcion_objeto }}</td>
            <td style="border: 1px solid #000;">{{ $a->estado_integridad ? 'X' : '' }}</td>
            <td style="border: 1px solid #000;">{{ !$a->estado_integridad ? 'X' : '' }}</td>
            <td style="border: 1px solid #000;">{{ $a->porcentaje_integridad }}%</td>
            <td style="border: 1px solid #000;">{{ $a->estadoConservacion?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $a->diagnostico_general }}</td>
            <td style="border: 1px solid #000;">{!! strlen($a->proceso_trabajo) > 80 ? wordwrap($a->proceso_trabajo, 80, '<br>', true) : $a->proceso_trabajo !!}</td>
            <td style="border: 1px solid #000;">{{ $a->indentificacion_fotos }}</td>
            <td style="border: 1px solid #000;">{!! $a->responsables?->map(fn($resp)=>$resp->nombre_completo)->join("<br>") !!}</td>
            <td style="border: 1px solid #000;">{{ $a->observaciones }}</td>
            <td style="border: 1px solid #000;">{{ $a->fecha_inicio_format }}</td>
            <td style="border: 1px solid #000;">{{ $a->fecha_fin_format }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
