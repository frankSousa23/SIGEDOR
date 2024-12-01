<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detalles del Profesor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            margin-left: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SIGEDOR - Información del Profesor</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="section">
        <h2>Información Personal</h2>
        <p><span class="label">Nombre:</span> <span class="value">{{ $teacher->name }}</span></p>
        <p><span class="label">Email:</span> <span class="value">{{ $teacher->email }}</span></p>
        <p><span class="label">Teléfono:</span> <span class="value">{{ $teacher->phone }}</span></p>
    </div>

    <div class="section">
        <h2>Información Institucional</h2>
        <p><span class="label">Sede:</span> <span class="value">{{ $teacher->headquarters->name }}</span></p>
        <p><span class="label">Área:</span> <span class="value">{{ $teacher->area->name }}</span></p>
        <p><span class="label">Dedicación:</span> <span class="value">{{ $teacher->dedication->name }}</span></p>
    </div>

    <div class="footer">
        <p>SIGEDOR - Sistema de Gestión Docente</p>
        <p>Documento generado por {{ $user->name }}</p>
    </div>
</body>
</html>
