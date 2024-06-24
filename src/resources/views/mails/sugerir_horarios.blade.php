<!DOCTYPE html>
<html>
<head>
    <title>Sugerencia de Horarios</title>
</head>
<body>
    <h1>Sugerencia de Horarios</h1>
    <p>Estimado(a) {{ $solicitud->docente->usuario->nombre }},</p>
    <p>Se han registrado las siguientes sugerencias de horarios:</p>
    <ul>
        <li>Horario: {{ $solicitud->horarioDisponible->fecha }} - {{ $solicitud->horarioDisponible->hora_inicio }} a {{ $solicitud->horarioDisponible->hora_fin }}</li>
        <li>Capacidad: {{ $solicitud->capacidad }}</li>
        <li>Grupo: {{ $solicitud->grupo->nombre }}</li>
        <li>Tipo de Reserva: {{ $solicitud->tipo_reserva }}</li>
    </ul>
    <p>Por favor, tenga en cuenta que esta sugerencia solo será válida por 24 horas. Después de este periodo, la sugerencia expirará y deberá realizar una nueva solicitud si es necesario.</p>
</body>
</html>
