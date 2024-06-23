<!DOCTYPE html>
<html>
<head>
    <title>Solicitud Rechazada</title>
</head>
<body>
    <h1>Su solicitud ha sido rechazada</h1>
    <p>Detalles de la Solicitud:</p>
    <p><strong>ID de Solicitud:</strong> {{ $solicitud->id }}</p>
    <p><strong>Docente:</strong> {{ $docente->nombre }} {{ $docente->apellido }}</p>
    <p><strong>Ambiente:</strong> {{ $solicitud->horarioDisponible->ambiente->nombre }}</p>
    <p><strong>Fecha de reserva:</strong> {{ $solicitud->horarioDisponible->fecha }}</p>
    <p><strong>Hora de reserva:</strong> {{ $solicitud->horarioDisponible->hora_inicio }} - {{ $solicitud->horarioDisponible->hora_fin }}</p>
    <p>Este es un correo generado automáticamente con los detalles de la solicitud de reserva.</p>
</body>
</html>
