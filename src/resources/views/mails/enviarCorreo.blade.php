<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Solicitud de Reserva</title>
</head>
<body>
    <h1>Solicitud de Reserva de Ambiente {{ strtoupper($estado) }}</h1>
    <p>Este es un correo generado automáticamente con los detalles de la solicitud de reserva.</p>
    <p><strong>Docente:</strong> {{ $solicitud->docente->nombre }}</p>
    <p><strong>Ambiente:</strong> {{ $solicitud->horarioDisponible->ambiente->nombre }}</p>
    <p><strong>Fecha:</strong> {{ $solicitud->horarioDisponible->fecha }}</p>
    <p><strong>Hora:</strong> {{ $solicitud->horarioDisponible->hora_inicio }} - {{ $solicitud->horarioDisponible->hora_fin }}</p>

    @if ($estado == 'rechazada')
        <p>Lamentablemente, su solicitud ha sido rechazada. Por favor, intente solicitar otro horario disponible.</p>
    @else
        <p>Su solicitud ha sido aprobada exitosamente.</p>
    @endif
</body>
</html>
