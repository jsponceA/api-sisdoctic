<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Proyecto</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">N° Doc Recep</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Asunto</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Referencia</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Emisión</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Recepción</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Entrega Área</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Tipo Documento</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Tipo Doc Clasificación</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Especialidad</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Destino</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Clave</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Situación</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Prioridad</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Respuesta</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">N° Docs Resp</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Días Sin Responder</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Fecha Para Responder</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold; border: 1px solid #000; text-align: center">Atención</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($registros as $r)
        <tr>
            <td style="border: 1px solid #000;">{{ $r->proyecto?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $r->num_doc_recep }}</td>
            <td style="border: 1px solid #000;">{{ $r->asunto }}</td>
            <td style="border: 1px solid #000;">{{ $r->referencia }}</td>
            <td style="border: 1px solid #000;">{{ $r->fecha_emision_format }}</td>
            <td style="border: 1px solid #000;">{{ $r->fecha_recepcion_format }}</td>
            <td style="border: 1px solid #000;">{{ $r->fecha_entrega_area_format }}</td>
            <td style="border: 1px solid #000;">{{ $r->tipoDocumento?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $r->tipoDocumentoClasificacion?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $r->especialidad?->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $r->destino }}</td>
            <td style="border: 1px solid #000;">{{ $r->clave }}</td>
            <td style="border: 1px solid #000;">{{ $r->situacion == 'R' ? 'Respondido' : 'Sin Responder' }}</td>
            <td style="border: 1px solid #000;">{{ $r->prioridad }}</td>
            <td style="border: 1px solid #000;">{{ $r->fecha_respuesta_format }}</td>
            <td style="border: 1px solid #000;">{{ $r->num_docs_resp }}</td>
            <td style="border: 1px solid #000;">{{ $r->num_dias_sin_responder }}</td>
            <td style="border: 1px solid #000;">{{ $r->fecha_para_responder_format }}</td>
            <td style="border: 1px solid #000;">{{ $r->atencion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
