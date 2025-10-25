<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

@php
    // Funciones de cálculo (igual que en ficha_recepcion)
    $calcularDiasSinResponder = function($fechaEntregaArea, $fechaRespuesta = null) {
        if (!$fechaEntregaArea) return '-';
        $fechaFinal = $fechaRespuesta ? \Carbon\Carbon::parse($fechaRespuesta) : now();
        $fechaEntrega = \Carbon\Carbon::parse($fechaEntregaArea);
        return abs((int) $fechaEntrega->diffInDays($fechaFinal, false));
    };

    $calcularFechaParaResponder = function($fechaEntregaArea, $diasPlazo) {
        if (!$fechaEntregaArea || !$diasPlazo) return '-';
        $fechaEntrega = \Carbon\Carbon::parse($fechaEntregaArea);
        return $fechaEntrega->copy()->addDays($diasPlazo);
    };

    $calcularDiasDefasados = function($fechaParaResponder, $fechaRespuesta = null) {
        if (!$fechaParaResponder || $fechaParaResponder === '-') return '-';
        $fechaFinal = $fechaRespuesta ? \Carbon\Carbon::parse($fechaRespuesta) : now();
        $fechaLimite = is_string($fechaParaResponder) ? \Carbon\Carbon::parse($fechaParaResponder) : $fechaParaResponder;
        if ($fechaFinal->isAfter($fechaLimite)) {
            return abs((int) $fechaLimite->diffInDays($fechaFinal, false));
        }
        return 0;
    };
@endphp

<table>
    <thead>
    <!-- PRIMERA FILA: Secciones agrupadas -->
    <tr>
        <th rowspan="2" style="background-color: #d1d5db; color: #000; font-weight: bold; border: 1px solid #000; text-align: center; vertical-align: middle;">MES</th>
        <th rowspan="2" style="background-color: #d1d5db; color: #000; font-weight: bold; border: 1px solid #000; text-align: center; vertical-align: middle;">PROYECTO</th>
        <th colspan="10" style="background-color: #fde047; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">RECEPCIÓN</th>
        <th colspan="5" style="background-color: #93c5fd; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">RESPUESTA</th>
        <th colspan="6" style="background-color: #86efac; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">ESTADO DE RESPUESTA</th>
    </tr>

    <!-- SEGUNDA FILA: Columnas específicas -->
    <tr>
        <!-- RECEPCIÓN (10 columnas) -->
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Fecha Emisión</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Fecha Recepción</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Fecha Entrega Área</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Tipo Documento</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">N° Doc. Recep.</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Asunto</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Cant. Docs Adjuntos</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Tipo Doc. Clasificación</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Especialidad</th>
        <th style="background-color: #fef08a; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Destino</th>

        <!-- RESPUESTA (5 columnas) -->
        <th style="background-color: #bfdbfe; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Fecha</th>
        <th style="background-color: #bfdbfe; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Cant. Documentos</th>
        <th style="background-color: #bfdbfe; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">N° Doc. Resp.</th>
        <th style="background-color: #bfdbfe; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Atención</th>
        <th style="background-color: #bfdbfe; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Acciones u Observaciones</th>

        <!-- ESTADO DE RESPUESTA (6 columnas) -->
        <th style="background-color: #bbf7d0; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Estado</th>
        <th style="background-color: #bbf7d0; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Prioridad</th>
        <th style="background-color: #bbf7d0; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Situación</th>
        <th style="background-color: #bbf7d0; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">N° Días sin Responder</th>
        <th style="background-color: #bbf7d0; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Fecha para Responder</th>
        <th style="background-color: #bbf7d0; color: #000; font-weight: bold; border: 1px solid #000; text-align: center;">Días Defasados</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($registros as $r)
        @php
            // Obtener días de plazo del tipo de documento del proyecto
            $diasPlazo = null;
            if ($r->proyecto && $r->tipo_documento_id) {
                $tipoDocProyecto = $r->proyecto->tiposDocumento->firstWhere('tipo_documento_id', $r->tipo_documento_id);
                if ($tipoDocProyecto) {
                    $diasPlazo = $tipoDocProyecto->dias_plazo;
                }
            }

            // Calcular valores
            $diasSinResponder = $calcularDiasSinResponder($r->fecha_entrega_area, $r->fecha_respuesta);
            $fechaParaResponder = $calcularFechaParaResponder($r->fecha_entrega_area, $diasPlazo);
            $diasDefasados = $calcularDiasDefasados($fechaParaResponder, $r->fecha_respuesta);

            // Formatear mes
            $mes = $r->fecha_recepcion ? \Carbon\Carbon::parse($r->fecha_recepcion)->locale('es')->isoFormat('MMMM - dddd') : '-';

            // Contar documentos
            $cantDocAdjuntos = $r->documentosAdjuntos ? $r->documentosAdjuntos->count() : 0;
            $cantDocRespuesta = $r->documentosRespuesta ? $r->documentosRespuesta->count() : 0;

            // Formatear estado
            $estadoTexto = '-';
            if ($r->estado_documento == 'aprobado') {
                $estadoTexto = 'Aprobado';
            } elseif ($r->estado_documento == 'observado') {
                $estadoTexto = 'Observado';
            } elseif ($r->estado_documento == 'en_proceso') {
                $estadoTexto = 'En Proceso';
            }

            // Formatear prioridad
            $prioridadTexto = '-';
            if ($r->prioridad == 1) {
                $prioridadTexto = 'Baja';
            } elseif ($r->prioridad == 2) {
                $prioridadTexto = 'Media';
            } elseif ($r->prioridad == 3) {
                $prioridadTexto = 'Alta';
            }

            // Formatear situación
            $situacionTexto = $r->situacion == 'R' ? 'Respondido' : ($r->situacion == 'SR' ? 'Sin Responder' : '-');

            // Responsables destino
            $destinoTexto = '';
            if ($r->responsablesDestino && $r->responsablesDestino->count() > 0) {
                $destinoTexto = $r->responsablesDestino->map(function($rd) {
                    return $rd->responsable?->nombre_completo ?? '-';
                })->implode(', ');
            }
        @endphp
        <tr>
            <!-- MES -->
            <td style="border: 1px solid #000; background-color: #f3f4f6;">{{ $mes }}</td>

            <!-- PROYECTO -->
            <td style="border: 1px solid #000; background-color: #f3f4f6;">{{ $r->proyecto?->nombre ?? '-' }}</td>

            <!-- ========== RECEPCIÓN ========== -->
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->fecha_emision_format ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->fecha_recepcion_format ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->fecha_entrega_area_format ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->tipoDocumento?->nombre ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3; font-weight: bold;">{{ $r->num_doc_recep ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->asunto ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3; text-align: center;">{{ $cantDocAdjuntos }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->tipoDocumentoClasificacion?->nombre ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $r->especialidad?->nombre ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #fef9c3;">{{ $destinoTexto ?: ($r->destino ?? '-') }}</td>

            <!-- ========== RESPUESTA ========== -->
            <td style="border: 1px solid #000; background-color: #dbeafe;">{{ $r->fecha_respuesta_format ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #dbeafe; text-align: center;">{{ $cantDocRespuesta }}</td>
            <td style="border: 1px solid #000; background-color: #dbeafe;">{{ $r->num_docs_resp ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #dbeafe;">{{ $r->atencion ?? '-' }}</td>
            <td style="border: 1px solid #000; background-color: #dbeafe;">{{ $r->acciones_observaciones ?? '-' }}</td>

            <!-- ========== ESTADO DE RESPUESTA ========== -->
            <td style="border: 1px solid #000; background-color: #dcfce7;">{{ $estadoTexto }}</td>
            <td style="border: 1px solid #000; background-color: #dcfce7;">{{ $prioridadTexto }}</td>
            <td style="border: 1px solid #000; background-color: #dcfce7;">{{ $situacionTexto }}</td>
            <td style="border: 1px solid #000; background-color: #dcfce7; text-align: center;">{{ $diasSinResponder !== '-' ? $diasSinResponder . ' días' : '-' }}</td>
            <td style="border: 1px solid #000; background-color: #dcfce7;">{{ $fechaParaResponder !== '-' ? $fechaParaResponder->format('d/m/Y') : '-' }}</td>
            <td style="border: 1px solid #000; background-color: #dcfce7; text-align: center;">{{ $diasDefasados !== '-' ? $diasDefasados . ' días' : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
