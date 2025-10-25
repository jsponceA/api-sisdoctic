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
        <h1>Documentos Pendientes</h1>
    </div>

    <div class="content">
        <div class="greeting">
            Hola <strong>{{ $user->name }}</strong>,
        </div>

        <p style="color: #555; line-height: 1.6;">
            Te informamos que tienes documentos que requieren tu atención:
        </p>

        <div style="display: table; width: 100%; margin: 30px 0;">
            @if($overdueCount > 0)
                <div style="display: table-cell; width: 50%; padding-right: 5px;">
                    <div class="stat-item">
                        <div class="stat-number">{{ $overdueCount }}</div>
                        <div class="stat-label">Atrasados</div>
                    </div>
                </div>
            @endif

            @if($noResponseCount > 0)
                <div style="display: table-cell; width: 50%; padding-left: 5px;">
                    <div class="stat-item">
                        <div class="stat-number">{{ $noResponseCount }}</div>
                        <div class="stat-label">Sin Respuesta</div>
                    </div>
                </div>
            @endif
        </div>

        @if($overdueCount > 0)
            <div class="alert-box danger">
                <strong>⏰ Documentos atrasados:</strong> Tienes {{ $overdueCount }}
                documento(s) que han superado su fecha límite.
            </div>
        @endif

        @if($noResponseCount > 0)
            <div class="alert-box">
                <strong>📋 Documentos sin respuesta:</strong> Hay {{ $noResponseCount }}
                documento(s) esperando tu revisión o respuesta.
            </div>
        @endif

        <h3 style="color: #333; margin-top: 30px;">Documentos Recientes:</h3>

        <div class="document-list">
            @foreach($documents as $document)
                <div class="document-item">
                    <div class="document-title">
                        {{ $document->title }}
                        @if($document->due_date < now())
                            <span class="badge badge-danger">Atrasado</span>
                        @else
                            <span class="badge badge-warning">Pendiente</span>
                        @endif
                    </div>
                    <div class="document-meta">
                        📅 Fecha límite: {{ $document->due_date->format('d/m/Y') }}
                        &nbsp;|&nbsp;
                        🕐 Creado: {{ $document->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}/documents" class="button">
                Ver Todos los Documentos
            </a>
        </div>

        <p style="color: #6c757d; font-size: 14px; margin-top: 30px;">
            Por favor, revisa estos documentos lo antes posible para mantener tus procesos al día.
        </p>
    </div>

    <div class="footer">
        <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        <p style="margin-top: 10px;">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </p>
    </div>
</div>
</body>
</html>
