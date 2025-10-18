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
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Responsables</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Tipos de Documento</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Inicio</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Fin</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Descripción</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($proyectos as $p)
        <tr>
            <td style="border: 1px solid #000;">{{ $p->codigo_proyecto }}</td>
            <td style="border: 1px solid #000;">{{ $p->nombre }}</td>
            <td style="border: 1px solid #000;">
                @if($p->responsables && $p->responsables->count() > 0)
                    @foreach($p->responsables as $resp)
                        {{ $resp->responsable?->nombre_completo }}@if($resp->especialidad) ({{ $resp->especialidad?->nombre }})@endif
                        @if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
            <td style="border: 1px solid #000;">
                @if($p->tiposDocumento && $p->tiposDocumento->count() > 0)
                    @foreach($p->tiposDocumento as $td)
                        {{ $td->tipoDocumento?->nombre }} ({{ $td->dias_plazo }} días)@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
            <td style="border: 1px solid #000;">{{ $p->fecha_inicio_format }}</td>
            <td style="border: 1px solid #000;">{{ $p->fecha_fin_format }}</td>
            <td style="border: 1px solid #000;">{{ $p->descripcion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
