<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #2563eb;">Notificación de Planilla</h2>
    
    <p>Hola <strong>{{ $detalle->empleado->nombres }} {{ $detalle->empleado->apellidos }}</strong>,</p>
    
    <p>Adjunto a este correo encontrarás tu boleta de pago correspondiente a la planilla del mes de <strong>{{ $detalle->carga->mes_planilla }} {{ $detalle->carga->anio_planilla }}</strong>.</p>
    
    <p>Por favor, revisa el documento adjunto (PDF) para ver el desglose detallado de tus ingresos y descuentos.</p>
    
    <br>
    <p>Atentamente,</p>
    <p><strong>Departamento de Recursos Humanos</strong><br>
    {{ $detalle->empleado->empresa->nombre ?? 'Nuestra Empresa' }}</p>
</body>
</html>