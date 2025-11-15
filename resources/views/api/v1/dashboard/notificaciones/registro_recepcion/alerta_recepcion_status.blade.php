<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Pendientes</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f7;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }
        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .alert-box.danger {
            background-color: #f8d7da;
            border-left-color: #dc3545;
        }
        .stats {
            display: table;
            width: 100%;
            margin: 30px 0;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .stat-item + .stat-item {
            padding-left: 10px;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .document-list {
            margin: 25px 0;
        }
        .document-item {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        .document-title {
            font-weight: 600;
            color: #333333;
            margin-bottom: 5px;
        }
        .document-meta {
            font-size: 13px;
            color: #6c757d;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 8px;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
        }
        .info-table {
            width: 100%;
            margin: 15px 0;
            background-color: #ffffff;
        }
        .info-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            width: 40%;
        }
        .info-value {
            color: #212529;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333333;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
        }
        .priority-high {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }
        .priority-medium {
            background-color: #ffc107;
            color: #333;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }
        .priority-low {
            background-color: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
            .stats {
                display: block;
            }
            .stat-item {
                display: block;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="icon">⚠️</div>
        <h1>Alerta de Documentos Pendientes</h1>
    </div>

    <div class="content">
        <div class="greeting">
            Estimado Usuario,
        </div>

        <p style="color: #495057; line-height: 1.6;">
            Le informamos que existen documentos que requieren su atención inmediata. A continuación,
            encontrará el detalle de los registros de recepción que están <strong>vencidos</strong> o
            <strong>próximos a vencer</strong>.
        </p>

        <!-- Estadísticas Generales -->
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number">3</div>
                <div class="stat-label">Vencidos</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">2</div>
                <div class="stat-label">Por Vencer</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">4</div>
                <div class="stat-label">Alta Prioridad</div>
            </div>
        </div>

        <!-- Alerta de documentos vencidos -->
        <div class="alert-box danger">
            <strong>⚠️ Atención:</strong> Tiene <strong>3 documentos vencidos</strong> que requieren respuesta inmediata.
        </div>

        <!-- Sección: Documentos Vencidos -->
        <div class="section-title">📋 Documentos Vencidos</div>

        <div class="document-list">
            <!-- Documento 1 -->
            <div class="document-item" style="border-left-color: #dc3545;">
                <div class="document-title">
                    CO-PE-1PE140-GP-TC-1826
                    <span class="badge badge-danger">VENCIDO</span>
                    <span class="priority-medium">MEDIA</span>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Proyecto:</td>
                        <td class="info-value">Construcción de la Sede Unica Institucional de la Superintendencia de Banca, Seguros y AFP – "SBS" Saldo de Obra</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tipo de Documento:</td>
                        <td class="info-value">SUBMITTAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Formato:</td>
                        <td class="info-value">CARTA DIGITAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Especialidad:</td>
                        <td class="info-value">COMUNICACIONES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Asunto:</td>
                        <td class="info-value">Remisión de Submittal N°293 Rev.01: Fichas técnicas de CPU's mini del sistema de audio y video - Todos los niveles</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Emisión:</td>
                        <td class="info-value">10/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Recepción:</td>
                        <td class="info-value">10/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Entrega Área:</td>
                        <td class="info-value" style="color: #0066cc; font-weight: 600;">10/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Límite Respuesta:</td>
                        <td class="info-value" style="color: #dc3545; font-weight: 600;">14/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Días de Retraso:</td>
                        <td class="info-value" style="color: #dc3545; font-weight: 600;">11 días</td>
                    </tr>
                    <tr>
                        <td class="info-label">Responsable Destino:</td>
                        <td class="info-value">LUIS ALBERTO BELLODAS PAREDES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Estado:</td>
                        <td class="info-value"><span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">APROBADO</span></td>
                    </tr>
                    <tr>
                        <td class="info-label">Situación:</td>
                        <td class="info-value"><span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">RESPONDIDO</span></td>
                    </tr>
                </table>
            </div>

            <!-- Documento 2 -->
            <div class="document-item" style="border-left-color: #dc3545;">
                <div class="document-title">
                    CO-PE-1PE140-GP-TC-1839
                    <span class="badge badge-danger">VENCIDO</span>
                    <span class="priority-medium">MEDIA</span>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Proyecto:</td>
                        <td class="info-value">Construcción de la Sede Unica Institucional de la Superintendencia de Banca, Seguros y AFP – "SBS" Saldo de Obra</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tipo de Documento:</td>
                        <td class="info-value">SUBMITTAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Formato:</td>
                        <td class="info-value">CARTA DIGITAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Especialidad:</td>
                        <td class="info-value">COMUNICACIONES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Asunto:</td>
                        <td class="info-value">Remisión de Submittal Nº303 Rev.01: Ficha técnica de cable HDMI para fibra óptica - Todos los niveles</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Emisión:</td>
                        <td class="info-value">11/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Recepción:</td>
                        <td class="info-value">11/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Entrega Área:</td>
                        <td class="info-value" style="color: #0066cc; font-weight: 600;">11/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Límite Respuesta:</td>
                        <td class="info-value" style="color: #dc3545; font-weight: 600;">15/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Días de Retraso:</td>
                        <td class="info-value" style="color: #dc3545; font-weight: 600;">10 días</td>
                    </tr>
                    <tr>
                        <td class="info-label">Responsable Destino:</td>
                        <td class="info-value">LUIS ALBERTO BELLODAS PAREDES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Estado:</td>
                        <td class="info-value"><span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">APROBADO</span></td>
                    </tr>
                    <tr>
                        <td class="info-label">Situación:</td>
                        <td class="info-value"><span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">RESPONDIDO</span></td>
                    </tr>
                </table>
            </div>

            <!-- Documento 3 -->
            <div class="document-item" style="border-left-color: #dc3545;">
                <div class="document-title">
                    CO-PE-1PE140-GP-TC-1731
                    <span class="badge badge-danger">VENCIDO</span>
                    <span class="priority-medium">MEDIA</span>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Proyecto:</td>
                        <td class="info-value">Construcción de la Sede Unica Institucional de la Superintendencia de Banca, Seguros y AFP – "SBS" Saldo de Obra</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tipo de Documento:</td>
                        <td class="info-value">SUBMITTAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Formato:</td>
                        <td class="info-value">CARTA DIGITAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Especialidad:</td>
                        <td class="info-value">COMUNICACIONES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Asunto:</td>
                        <td class="info-value">Remisión de Submittal Nº357 Rev.00: Fichas técnicas complementarias de control de iluminación - Todos los niveles</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Emisión:</td>
                        <td class="info-value">26/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Recepción:</td>
                        <td class="info-value">26/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Entrega Área:</td>
                        <td class="info-value" style="color: #0066cc; font-weight: 600;">26/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Límite Respuesta:</td>
                        <td class="info-value" style="color: #dc3545; font-weight: 600;">30/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Días de Retraso:</td>
                        <td class="info-value" style="color: #dc3545; font-weight: 600;">25 días</td>
                    </tr>
                    <tr>
                        <td class="info-label">Responsable Destino:</td>
                        <td class="info-value">LUIS ALBERTO BELLODAS PAREDES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Estado:</td>
                        <td class="info-value"><span style="background-color: #ffc107; color: #333; padding: 2px 8px; border-radius: 3px; font-size: 11px;">OBSERVADO</span></td>
                    </tr>
                    <tr>
                        <td class="info-label">Situación:</td>
                        <td class="info-value"><span style="background-color: #dc3545; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">SIN RESPONDER</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Sección: Documentos Por Vencer -->
        <div class="section-title">⏰ Documentos Próximos a Vencer (Quedan menos de 3 días)</div>

        <div class="alert-box">
            <strong>📌 Recordatorio:</strong> Los siguientes documentos vencen en los próximos días.
            Por favor, priorice su atención.
        </div>

        <div class="document-list">
            <!-- Documento por vencer 1 -->
            <div class="document-item" style="border-left-color: #ffc107;">
                <div class="document-title">
                    CO-PE-1PE410-GP-TC-1684
                    <span class="badge badge-warning">POR VENCER</span>
                    <span class="priority-medium">MEDIA</span>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Proyecto:</td>
                        <td class="info-value">Construcción de la Sede Unica Institucional de la Superintendencia de Banca, Seguros y AFP – "SBS" Saldo de Obra</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tipo de Documento:</td>
                        <td class="info-value">TRANSMITTAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Formato:</td>
                        <td class="info-value">CARTA DIGITAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Especialidad:</td>
                        <td class="info-value">COMUNICACIONES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Asunto:</td>
                        <td class="info-value">Remisión de Transmittal Nº 048 Rev.01: Planos de control de iluminación (Sótanos, Semisótanos y Azotea)</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Emisión:</td>
                        <td class="info-value">14/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Recepción:</td>
                        <td class="info-value">14/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Entrega Área:</td>
                        <td class="info-value" style="color: #0066cc; font-weight: 600;">14/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Límite Respuesta:</td>
                        <td class="info-value" style="color: #ffc107; font-weight: 600;">16/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Días Restantes:</td>
                        <td class="info-value" style="color: #ffc107; font-weight: 600;">2 días</td>
                    </tr>
                    <tr>
                        <td class="info-label">Responsable Destino:</td>
                        <td class="info-value">LUIS ALBERTO BELLODAS PAREDES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Estado:</td>
                        <td class="info-value"><span style="background-color: #0066cc; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">EN PROCESO</span></td>
                    </tr>
                    <tr>
                        <td class="info-label">Situación:</td>
                        <td class="info-value"><span style="background-color: #dc3545; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">SIN RESPONDER</span></td>
                    </tr>
                </table>
            </div>

            <!-- Documento por vencer 2 -->
            <div class="document-item" style="border-left-color: #ffc107;">
                <div class="document-title">
                    CO-PE-1PE140-GP-TC-1749
                    <span class="badge badge-warning">POR VENCER</span>
                    <span class="priority-medium">MEDIA</span>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Proyecto:</td>
                        <td class="info-value">Construcción de la Sede Unica Institucional de la Superintendencia de Banca, Seguros y AFP – "SBS" Saldo de Obra</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tipo de Documento:</td>
                        <td class="info-value">SUBMITTAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Formato:</td>
                        <td class="info-value">CARTA DIGITAL</td>
                    </tr>
                    <tr>
                        <td class="info-label">Especialidad:</td>
                        <td class="info-value">COMUNICACIONES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Asunto:</td>
                        <td class="info-value">Remisión de Submittal N°358 Rev.00: Fichas técnicas de gabinete secundario de comunicaciones - Todos los niveles</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Emisión:</td>
                        <td class="info-value">30/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha de Recepción:</td>
                        <td class="info-value">30/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Entrega Área:</td>
                        <td class="info-value" style="color: #0066cc; font-weight: 600;">30/09/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Fecha Límite Respuesta:</td>
                        <td class="info-value" style="color: #ffc107; font-weight: 600;">04/10/2025</td>
                    </tr>
                    <tr>
                        <td class="info-label">Días Restantes:</td>
                        <td class="info-value" style="color: #ffc107; font-weight: 600;">1 día</td>
                    </tr>
                    <tr>
                        <td class="info-label">Responsable Destino:</td>
                        <td class="info-value">LUIS ALBERTO BELLODAS PAREDES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Estado:</td>
                        <td class="info-value"><span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">APROBADO</span></td>
                    </tr>
                    <tr>
                        <td class="info-label">Situación:</td>
                        <td class="info-value"><span style="background-color: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">RESPONDIDO</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Resumen de situación -->
        <div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 30px 0; border-left: 4px solid #0066cc;">
            <h3 style="margin-top: 0; color: #0066cc;">📊 Resumen de Situación</h3>
            <ul style="color: #495057; line-height: 1.8; margin: 10px 0;">
                <li><strong>Total de documentos pendientes:</strong> 5 documentos</li>
                <li><strong>Documentos vencidos:</strong> 3 documentos</li>
                <li><strong>Documentos por vencer (3 días o menos):</strong> 2 documentos</li>
                <li><strong>Proyecto:</strong> Construcción de la Sede Unica Institucional de la SBS</li>
                <li><strong>Especialidad:</strong> COMUNICACIONES</li>
                <li><strong>Tipos de documento:</strong> SUBMITTAL, TRANSMITTAL</li>
                <li><strong>Formato principal:</strong> CARTA DIGITAL</li>
            </ul>
        </div>

        <!-- Llamado a la acción -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/dashboard/registro-recepciones" class="button">
                Ver Todos los Documentos Pendientes
            </a>
        </div>

        <p style="color: #6c757d; font-size: 13px; line-height: 1.6; margin-top: 30px;">
            <strong>Nota importante:</strong> Este correo es generado automáticamente por el sistema de gestión documental.
            Le recomendamos revisar y dar respuesta a los documentos vencidos a la brevedad posible para evitar
            penalidades y mantener el flujo de trabajo de los proyectos. Los plazos de respuesta varían según el tipo
            de documento (SUBMITTAL: 4 días, TRANSMITTAL: 2 días).
        </p>
    </div>

    <div class="footer">
        <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        <p style="margin-top: 10px;">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </p>
        <p style="margin-top: 15px; font-size: 12px;">
            Sistema de Gestión Documental - Superintendencia de Banca, Seguros y AFP
        </p>
    </div>
</div>
</body>
</html>
