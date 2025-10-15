<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Proyecto</title>
    <style>
        @page {
            margin: 80px 25px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }

        .titulo-principal {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .section-header {
            background-color: #2c61ed;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }

        .label-cell {
            width: 30%;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .content-cell {
            width: 70%;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
        }

        .info-row td {
            padding: 8px;
        }

        .text-area {
            min-height: 60px;
        }
    </style>
</head>
<body>
<header>
    <img src="{{public_path('img/logo_mincul.jpg')}}" style="width: 377px;height: 45px">
    <img src="{{public_path('img/logo_puruchuco.png')}}" style="width: 111px;height: 60px;float: right">
</header>

<div class="titulo-principal">FICHA DE PROYECTO</div>

<!-- I. DATOS DE IDENTIFICACIÓN -->
<table>
    <tr>
        <td colspan="4" class="section-header">I. DATOS DE IDENTIFICACIÓN</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Código de Proyecto:</td>
        <td class="content-cell">{{ $proyecto->codigo_proyecto }}</td>
        <td class="label-cell">Estado:</td>
        <td class="content-cell">{{ $proyecto->estado }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Nombre del Proyecto:</td>
        <td colspan="3" class="content-cell">{{ $proyecto->nombre_proyecto }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fecha de Inicio:</td>
        <td class="content-cell">{{ $proyecto->fecha_inicio_format }}</td>
        <td class="label-cell">Fecha de Fin:</td>
        <td class="content-cell">{{ $proyecto->fecha_fin_format }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Ubicación:</td>
        <td class="content-cell">{{ $proyecto->ubicacion }}</td>
        <td class="label-cell">Categoría:</td>
        <td class="content-cell">{{ $proyecto->categoria?->nombre }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Responsable:</td>
        <td colspan="3" class="content-cell">{{ $proyecto->responsable?->nombre_completo }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Descripción:</td>
        <td colspan="3" class="content-cell text-area">{{ $proyecto->descripcion }}</td>
    </tr>
</table>

<!-- II. DATOS TÉCNICOS -->
<table>
    <tr>
        <td colspan="4" class="section-header">II. DATOS TÉCNICOS</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Presupuesto:</td>
        <td class="content-cell">{{ $proyecto->presupuesto ? 'S/ ' . number_format($proyecto->presupuesto, 2) : '-' }}</td>
        <td class="label-cell">N° Beneficiarios:</td>
        <td class="content-cell">{{ $proyecto->numero_beneficiarios }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Fuente de Financiamiento:</td>
        <td colspan="3" class="content-cell">{{ $proyecto->fuente_financiamiento }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Objetivos:</td>
        <td colspan="3" class="content-cell text-area">{{ $proyecto->objetivos }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Alcance:</td>
        <td colspan="3" class="content-cell text-area">{{ $proyecto->alcance }}</td>
    </tr>
</table>

<!-- III. EQUIPO Y RECURSOS -->
<table>
    <tr>
        <td colspan="2" class="section-header">III. EQUIPO Y RECURSOS</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Equipo de Trabajo:</td>
        <td class="content-cell text-area">{{ $proyecto->equipo_trabajo }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Recursos Materiales:</td>
        <td class="content-cell text-area">{{ $proyecto->recursos_materiales }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Recursos Tecnológicos:</td>
        <td class="content-cell text-area">{{ $proyecto->recursos_tecnologicos }}</td>
    </tr>
</table>

<!-- IV. SEGUIMIENTO -->
<table>
    <tr>
        <td colspan="2" class="section-header">IV. SEGUIMIENTO</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Porcentaje de Avance:</td>
        <td class="content-cell">{{ $proyecto->porcentaje_avance }}%</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Resultados Obtenidos:</td>
        <td class="content-cell text-area">{{ $proyecto->resultados_obtenidos }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Dificultades Encontradas:</td>
        <td class="content-cell text-area">{{ $proyecto->dificultades_encontradas }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Lecciones Aprendidas:</td>
        <td class="content-cell text-area">{{ $proyecto->lecciones_aprendidas }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Observaciones:</td>
        <td class="content-cell text-area">{{ $proyecto->observaciones }}</td>
    </tr>
    <tr class="info-row">
        <td class="label-cell">Recomendaciones:</td>
        <td class="content-cell text-area">{{ $proyecto->recomendaciones }}</td>
    </tr>
</table>

<!-- V. ACTIVIDADES DEL PROYECTO -->
@if($proyecto->actividades && $proyecto->actividades->count() > 0)
<table>
    <tr>
        <td colspan="5" class="section-header">V. ACTIVIDADES DEL PROYECTO</td>
    </tr>
    <tr>
        <th style="background-color: #f0f0f0;">Nombre Actividad</th>
        <th style="background-color: #f0f0f0;">Descripción</th>
        <th style="background-color: #f0f0f0;">Fecha Programada</th>
        <th style="background-color: #f0f0f0;">Responsable</th>
        <th style="background-color: #f0f0f0;">Estado</th>
    </tr>
    @foreach($proyecto->actividades as $actividad)
    <tr>
        <td>{{ $actividad->nombre_actividad }}</td>
        <td>{{ $actividad->descripcion_actividad }}</td>
        <td>{{ $actividad->fecha_programada ? \Carbon\Carbon::parse($actividad->fecha_programada)->format('d/m/Y') : '-' }}</td>
        <td>{{ $actividad->responsable_actividad }}</td>
        <td>{{ $actividad->estado_actividad }}</td>
    </tr>
    @endforeach
</table>
@endif

<!-- Footer con información de auditoría -->
<table style="margin-top: 30px;">
    <tr>
        <td colspan="2" style="background-color: #f0f0f0; font-weight: bold; text-align: center;">INFORMACIÓN DE AUDITORÍA</td>
    </tr>
    <tr>
        <td class="label-cell">Creado por:</td>
        <td>{{ $proyecto->creadoPor?->name }} - {{ $proyecto->fecha_creacion_format }}</td>
    </tr>
    @if($proyecto->modificadoPor)
    <tr>
        <td class="label-cell">Modificado por:</td>
        <td>{{ $proyecto->modificadoPor?->name }} - {{ $proyecto->fecha_modificacion_format }}</td>
    </tr>
    @endif
</table>

</body>
</html>

