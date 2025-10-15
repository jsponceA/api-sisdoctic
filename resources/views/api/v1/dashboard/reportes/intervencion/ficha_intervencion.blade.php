<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha de Intervención</title>
    <style>
        @page {
            margin: 100px 25px 80px 25px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
        }

        .titulo-principal {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitulo {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 3px 5px;
            text-align: left;
            vertical-align: top;
            font-size: 8pt;
        }

        .seccion-titulo {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            padding: 5px;
            text-align: center;
            font-size: 9pt;
        }

        .label-field {
            background-color: #D9E2F3;
            font-weight: bold;
            width: 25%;
            font-size: 7pt;
            text-align: center;
        }

        .value-field {
            background-color: white;
            font-size: 8pt;
        }

        .header-row {
            background-color: #D9E2F3;
            font-weight: bold;
            text-align: center;
        }

        .proceso-table th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 8pt;
            padding: 4px;
        }

        .proceso-table td {
            font-size: 7pt;
            padding: 3px;
        }

        .foto-container {
            text-align: center;
            padding: 3px;
            min-height: 100px;
            vertical-align: middle;
        }

        .foto-container img {
            width: 100px !important;
            height: 100px !important;
        }

        .galeria-fotos {
            display: table;
            width: 100%;
        }

        .galeria-fotos td {
            width: 20%;
            text-align: center;
            padding: 5px;
            vertical-align: middle;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 70px;
            text-align: center;
        }

        .header-logos {
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-left {
            float: left;
            width: 50%;
            text-align: left;
        }

        .logo-right {
            float: right;
            width: 40%;
            text-align: right;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        .no-border {
            border: none !important;
        }

        .text-center {
            text-align: center;
        }

        .checkbox-item {
            display: inline-block;
            margin-right: 8px;
            font-size: 7pt;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            text-align: center;
            line-height: 10px;
            font-size: 8pt;
            margin-right: 3px;
            vertical-align: middle;
        }

        .checkbox.checked::before {
            content: "x";
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <div class="header-logos">
        <div class="logo-left">
            <img src="{{public_path('img/logo_mincul.jpg')}}" style="height: 50px;">
        </div>
        <div class="logo-right">
            <img src="{{public_path('img/logo_puruchuco.png')}}" style="height: 50px;">
        </div>
    </div>
</header>

<div class="titulo-principal">FICHA DE INTERVENCIÓN</div>
<div class="subtitulo">Museo de Sitio "Arturo Jiménez Borja" – Puruchuco</div>

<!-- Encabezado con datos principales -->
<table>
    <tr class="header-row">
        <td style="width: 20%;text-align: center;vertical-align: middle" rowspan="2">Ficha N°</td>
        <td style="width: 30%;text-align: center;vertical-align: middle"
            rowspan="2">{{ $intervencion->numero_ficha }}</td>
        <td style="width: 20%;">Fecha Recepción</td>
        <td style="width: 30%;">{{ $intervencion->fecha_recepcion_format }}</td>
    </tr>
    <tr class="header-row">
        <td>Fecha Entrega</td>
        <td>{{ $intervencion->fecha_entrega_format }}</td>
    </tr>
</table>

<!-- I. DATOS DE IDENTIFICACIÓN -->
<table>
    <tr>
        <td colspan="4" class="seccion-titulo">I. DATOS DE IDENTIFICACIÓN</td>
    </tr>
    <tr>
        <td class="label-field">N° Registro Nacional</td>
        <td class="value-field">{{ $intervencion->numero_registro_nacional }}</td>
        <td class="label-field">N° Inventario</td>
        <td class="value-field">{{ $intervencion->numero_inventario }}</td>
    </tr>
    <tr>
        <td class="label-field">Código Museo</td>
        <td class="value-field">{{ $intervencion->codigo_museo }}</td>
        <td class="label-field">Otros Códigos</td>
        <td class="value-field">{{ $intervencion->otros_codigos }}</td>
    </tr>
    <tr>
        <td class="label-field">Categoría</td>
        <td class="value-field">{{ $intervencion->categoria?->nombre }}</td>
        <td class="label-field">Denominación</td>
        <td class="value-field">{{ $intervencion->denominacion?->nombre }}</td>
    </tr>
    <tr>
        <td class="label-field">Cultura/Estilo/Autor</td>
        <td class="value-field">{{ $intervencion->cultura_estilo_autor }}</td>
        <td class="label-field">Período/Época</td>
        <td class="value-field">{{ $intervencion->periodo_epoca }}</td>
    </tr>
    <tr>
        <td class="label-field">Procedencia</td>
        <td colspan="3" class="value-field">{{ $intervencion->procedencia }}</td>
    </tr>
    <tr>
        <td class="label-field">Descripción</td>
        <td colspan="3" class="value-field" style="min-height: 60px;">{{ $intervencion->descripcion }}</td>
    </tr>
</table>

<!-- Vista General -->
@if($intervencion->vistaGenerales->isNotEmpty())
    <table>
        <tr>
            <td colspan="4" class="seccion-titulo">VISTA GENERAL</td>
        </tr>
        @foreach($intervencion->vistaGenerales->chunk(4) as $chunk)
            <tr>
                @foreach($chunk as $vista)
                    <td class="foto-container">
                        <img src="{{ public_path('storage/dashboard/intervenciones/' . $vista->foto) }}">
                    </td>
                @endforeach
                @for($i = count($chunk); $i < 4; $i++)
                    <td class="foto-container"></td>
                @endfor
            </tr>
        @endforeach
    </table>
@endif

<!-- II. DATOS TÉCNICOS -->
<table>
    <tr>
        <td colspan="8" class="seccion-titulo">II. DATOS TÉCNICOS</td>
    </tr>
    <tr>
        <td class="label-field" colspan="2">Material</td>
        <td colspan="6" class="value-field">{{ $intervencion->material }}</td>
    </tr>
    <tr>
        <td class="label-field" colspan="2">Técnicas</td>
        <td colspan="6" class="value-field">{{ $intervencion->tecnicas }}</td>
    </tr>
    <tr class="header-row">
        <td>Alto</td>
        <td>Largo</td>
        <td>Ancho</td>
        <td>Profundidad</td>
        <td>Diametro Máximo</td>
        <td>Diametro Mínimo</td>
        <td>Peso Inicial</td>
        <td>Peso Final</td>
    </tr>
    <tr class="text-center">
        <td class="value-field">{{ $intervencion->alto }} </td>
        <td class="value-field">{{ $intervencion->largo }} </td>
        <td class="value-field">{{ $intervencion->ancho }} </td>
        <td class="value-field">{{ $intervencion->profundidad }} </td>
        <td class="value-field">{{ $intervencion->diametro_maximo }} </td>
        <td class="value-field">{{ $intervencion->diametro_minimo }} </td>
        <td class="value-field">{{ $intervencion->peso_inicial }} </td>
        <td class="value-field">{{ $intervencion->peso_final }} </td>
    </tr>
</table>

<!-- III. ESTADO DE CONSERVACIÓN -->
<table>
    <tr>
        <td colspan="6" class="seccion-titulo">III. ESTADO DE CONSERVACIÓN</td>
    </tr>
    <tr>
        <td class="label-field">Integridad</td>
        <td colspan="3" class="value-field">
            @php
                $integridadValores = is_array($intervencion->integridad) ? $intervencion->integridad : [];
                $opcionesIntegridad = ['Completo', 'Incompleto', 'Fracturado'];
            @endphp
            <table style="width:100%; border:none;">
                <tr>
                    @foreach($opcionesIntegridad as $opcion)
                        <td style="border:none; padding:2px; width:33.33%;">
                            <span class="checkbox-item">
                                <span class="checkbox {{ in_array($opcion, $integridadValores) ? 'checked' : '' }}"></span>
                                {{ $opcion }}
                            </span>
                        </td>
                    @endforeach
                </tr>
            </table>
        </td>
        <td class="label-field">N° Fragmentos</td>
        <td class="value-field">{{ $intervencion->numero_fragmentos }}</td>
    </tr>
    <tr>
        <td class="label-field" style="vertical-align: top;">Tipo de Daño</td>
        <td colspan="5" class="value-field">
            @php
                $tipoDanoValores = is_array($intervencion->tipo_dano) ? $intervencion->tipo_dano : [];
                $opcionesTipoDano = [
                    "Abolladura",
                    "Craqueladura",
                    "Dilatación",
                    "Grieta",
                    "Pasmado",
                    "Alabeo",
                    "Decoloración",
                    "Disolución",
                    "Infestación",
                    "Picadura",
                    "Adhesivo",
                    "Deformación",
                    "Doblez",
                    "Impronta",
                    "Pudrición",
                    "Amarillamiento",
                    "Descolorido",
                    "Eflorescencia",
                    "Laguna",
                    "Pulverulencia",
                    "Ampolla",
                    "Descosido",
                    "Erosión",
                    "Mancha",
                    "Quemadura",
                    "Arrugas",
                    "Desgaste",
                    "Exfoliación",
                    "Marca",
                    "Rayadura",
                    "Concreción",
                    "Deshilachado",
                    "Faltante",
                    "Mineralización",
                    "Repinte",
                    "Contracción",
                    "Desprendimiento",
                    "Fisura",
                    "Orificio",
                    "Resequedad",
                    "Corrosión",
                    "Desportillado",
                    "Foxing",
                    "Oxidación",
                    "Rotura",
                    "Corte",
                    "Desecación",
                    "Friabilidad",
                    "Parche",
                    "Suciedad",
                ];
            @endphp
            <table style="width:100%; border:none;">
                @foreach(array_chunk($opcionesTipoDano, 5) as $fila)
                    <tr>
                        @foreach($fila as $opcion)
                            <td style="border:none; padding:2px; width:20%;">
                                <span class="checkbox-item">
                                    <span
                                        class="checkbox {{ in_array($opcion, $tipoDanoValores) ? 'checked' : '' }}"></span>
                                    {{ $opcion }}
                                </span>
                            </td>
                        @endforeach
                        @for($i = count($fila); $i < 5; $i++)
                            <td style="border:none; width:20%;"></td>
                        @endfor
                    </tr>
                @endforeach
            </table>
            @if($intervencion->otros_tipo_dano)
                <div style="margin-top: 5px;">
                    <strong>Otros:</strong> {{ $intervencion->otros_tipo_dano }}
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td class="label-field">Agentes de Deterioro</td>
        <td colspan="5" class="value-field">
            @php
                $agentesValores = is_array($intervencion->agentes_deterioro) ? $intervencion->agentes_deterioro : [];
                $opcionesAgentes = ['Físicos', 'Químicos', 'Biológicos'];
            @endphp
            <table style="width:100%; border:none;">
                <tr>
                    @foreach($opcionesAgentes as $opcion)
                        <td style="border:none; padding:2px; width:33.33%;">
                            <span class="checkbox-item">
                                <span class="checkbox {{ in_array($opcion, $agentesValores) ? 'checked' : '' }}"></span>
                                {{ $opcion }}
                            </span>
                        </td>
                    @endforeach
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="label-field">Condiciones Exposición/Almacenaje</td>
        <td colspan="5" class="value-field">{{ $intervencion->condiciones_exposicion_almacenaje }}</td>
    </tr>
    <tr>
        <td class="label-field">Intervenciones Anteriores</td>
        <td colspan="5" class="value-field">{{ $intervencion->intervenciones_anteriores }}</td>
    </tr>
    <tr>
        <td class="label-field">Análisis Realizados</td>
        <td colspan="5" class="value-field">{{ $intervencion->analisis_realizados }}</td>
    </tr>
    <tr>
        <td class="label-field">Diagnóstico</td>
        <td colspan="5" class="value-field" style="min-height: 50px;">{{ $intervencion->diagnostico }}</td>
    </tr>
</table>

<!-- Vistas de Detalle -->
@if($intervencion->vistaDetalles->isNotEmpty())
    <table>
        <tr>
            <td colspan="4" class="seccion-titulo">VISTAS DE DETALLE</td>
        </tr>
        @foreach($intervencion->vistaDetalles->chunk(4) as $chunk)
            <tr>
                @foreach($chunk as $vista)
                    <td class="foto-container">
                        <img src="{{ public_path('storage/dashboard/intervenciones/' . $vista->foto) }}">
                    </td>
                @endforeach
                @for($i = count($chunk); $i < 4; $i++)
                    <td class="foto-container"></td>
                @endfor
            </tr>
        @endforeach
    </table>
@endif


<!-- IV. PROCESO DE INTERVENCIÓN -->
<table>
    <tr>
        <td colspan="4" class="seccion-titulo">IV. PROCESO DE INTERVENCIÓN</td>
    </tr>
</table>

<table class="proceso-table">
    <thead>
    <tr>
        <th style="width: 20%;">Área o Material Intervenido</th>
        <th style="width: 20%;">Intervención/Tratamiento</th>
        <th style="width: 25%;">Insumo/Producto/Herramienta</th>
        <th style="width: 35%;">Procedimiento</th>
    </tr>
    </thead>
    <tbody>
    @forelse($intervencion->procesos as $proceso)
        <tr>
            <td>{{ $proceso->area_material_intervenido }}</td>
            <td>{{ $proceso->intervencion_tratamiento }}</td>
            <td>{{ $proceso->insumo_producto_herramiental }}</td>
            <td>{{ $proceso->procedimiento }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align: center; padding: 30px;">Sin procesos registrados</td>
        </tr>
    @endforelse
    </tbody>
</table>

<table>
    <tr>
        <td class="label-field" style="width: 30%;">Resultado/Tiempo Empleado</td>
        <td class="value-field">{{ $intervencion->resultado_tiempo_empleado }}</td>
    </tr>
    <tr>
        <td class="label-field">Embalaje/Soporte</td>
        <td class="value-field">{{ $intervencion->embalaje_soporte }}</td>
    </tr>
</table>

<!-- Vistas de la Intervención and Resultados -->
@if($intervencion->vistaIntervenciones->isNotEmpty())
    <table>
        <tr>
            <td colspan="4" class="seccion-titulo">VISTAS DE LA INTERVENCIÓN Y RESULTADOS</td>
        </tr>
        @foreach($intervencion->vistaIntervenciones->chunk(4) as $chunk)
            <tr>
                @foreach($chunk as $vista)
                    <td class="foto-container">
                        <img src="{{ public_path('storage/dashboard/intervenciones/' . $vista->foto) }}">
                    </td>
                @endforeach
                @for($i = count($chunk); $i < 4; $i++)
                    <td class="foto-container"></td>
                @endfor
            </tr>
        @endforeach
    </table>
@endif

<table>
    <tr>
        <td class="label-field" style="width: 30%;">Observaciones</td>
        <td class="value-field" style="min-height: 50px;">{{ $intervencion->observaciones }}</td>
    </tr>
    <tr>
        <td class="label-field">Recomendaciones</td>
        <td class="value-field" style="min-height: 50px;">{{ $intervencion->recomendaciones }}</td>
    </tr>
</table>

<!-- V. RESPONSABLES -->
<table>
    <tr>
        <td colspan="2" class="seccion-titulo">V. RESPONSABLES</td>
    </tr>
    <tr>
        <td class="label-field" style="width: 30%;">Director/Encargado</td>
        <td class="value-field">{{ $intervencion->directorEncargado?->nombre_completo ?? 'No asignado' }}</td>
    </tr>
    <tr>
        <td class="label-field">Conservador Responsable</td>
        <td class="value-field">{{ $intervencion->conservadorResponsable?->nombre_completo ?? 'No asignado' }}</td>
    </tr>
</table>

<!-- FIRMAS -->
<table style="margin-top: 50px;border: none">
    <tr style="border: none">
        <td style="width: 50%; text-align: center; padding: 40px 10px 10px 10px;border: none;">
            <div style="padding-top: 5px; margin: 0 30px;border-top: 1px solid black;">
                Director/Encargado
            </div>
        </td>
        <td style="width: 50%; text-align: center; padding: 40px 10px 10px 10px;border: none">
            <div style="padding-top: 5px; margin: 0 30px;border-top: 1px solid black">
                Conservador Responsable
            </div>
        </td>
    </tr>
</table>


</body>
</html>
