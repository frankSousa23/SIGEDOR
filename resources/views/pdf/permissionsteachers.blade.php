<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: landscape; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-top: 5px; }
        .footer-text { font-size: 12px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Universidad Nacional Experimental "Rómulo Gallegos"</div>
        <div class="subtitle">Control Académico - Reporte de Permisos de Docentes - {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Docente</th>
                <th>Número de Memo</th>
                <th>Tipo de Permiso</th>
                <th>¿Es Pagado?</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Tipo de Duración</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissionsteachers as $permission)
                <tr>
                    <td>{{ $permission->teacher_id }}</td>
                    <td>{{ $permission->memo_number }}</td>
                    <td>{{ $permission->type }}</td>
                    <td>{{ $permission->is_paid ? 'Sí' : 'No' }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->description ?? 'N/A' }}</td>
                    <td>{{ ucfirst($permission->status) }}</td>
                    <td>{{ ucfirst($permission->duration_type) }}</td>
                    <td>{{ $permission->start_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $permission->end_date->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">Total de permisos: {{ $permissionsteachers->count() }}</div>
        <div class="footer-text">SIGEDOR - Sistema para Gestión de Docentes Ordinarios</div>
        <div class="footer-text">Generado automáticamente por el sistema</div>
    </div>
</body>
</html>
