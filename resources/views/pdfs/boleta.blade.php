<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Pago</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; color: #2563eb; }
        .header p { margin: 5px 0 0 0; font-size: 14px; }
        
        .info-table { w-full; margin-bottom: 20px; border-collapse: collapse; width: 100%; }
        .info-table td { padding: 5px; border: 1px solid #ddd; }
        .info-table .bg-gray { background-color: #f3f4f6; font-weight: bold; width: 25%; }
        
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th { background-color: #2563eb; color: #fff; padding: 8px; text-align: left; }
        .details-table td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        
        .totales { width: 50%; float: right; border-collapse: collapse; }
        .totales td { padding: 8px; border: 1px solid #ddd; }
        .totales .total-final { background-color: #d1fae5; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $detalle->empleado->empresa->nombre ?? 'Empresa' }}</h1>
        <p>Boleta de Pago - {{ $detalle->carga->mes_planilla }} {{ $detalle->carga->anio_planilla }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="bg-gray">Código Empleado:</td>
            <td>{{ $detalle->empleado->codigo_empleado }}</td>
            <td class="bg-gray">Días Laborados:</td>
            <td>{{ $detalle->dias_laborados }}</td>
        </tr>
        <tr>
            <td class="bg-gray">Nombre Completo:</td>
            <td>{{ $detalle->empleado->nombres }} {{ $detalle->empleado->apellidos }}</td>
            <td class="bg-gray">Salario Diario:</td>
            <td>Q {{ number_format($detalle->salario_diario, 2) }}</td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th>Descripción de Ingresos</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sueldo Base</td>
                <td class="text-right">Q {{ number_format($detalle->sueldo_base, 2) }}</td>
            </tr>
            <tr>
                <td>Horas Extras</td>
                <td class="text-right">Q {{ number_format($detalle->horas_extras, 2) }}</td>
            </tr>
            <tr>
                <td>Bonificación Decreto (Ley)</td>
                <td class="text-right">Q {{ number_format($detalle->bonificacion_ley, 2) }}</td>
            </tr>
            <tr>
                <td>Bonificación Extra</td>
                <td class="text-right">Q {{ number_format($detalle->bonificacion_extra, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th style="background-color: #dc2626;">Descripción de Descuentos</th>
                <th class="text-right" style="background-color: #dc2626;">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Descuento IGSS</td>
                <td class="text-right">Q {{ number_format($detalle->desc_igss, 2) }}</td>
            </tr>
            <tr>
                <td>Descuento ISR</td>
                <td class="text-right">Q {{ number_format($detalle->isr, 2) }}</td>
            </tr>
            <tr>
                <td>Descuentos Varios</td>
                <td class="text-right">Q {{ number_format($detalle->descuentos_varios, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totales">
        <tr>
            <td class="bg-gray" style="text-align: right; font-weight: bold;">Salario Devengado:</td>
            <td class="text-right">Q {{ number_format($detalle->salario, 2) }}</td>
        </tr>
        <tr>
            <td class="bg-gray" style="text-align: right; font-weight: bold;">Salario Líquido a Recibir:</td>
            <td class="text-right total-final">Q {{ number_format($detalle->liquido, 2) }}</td>
        </tr>
    </table>

</body>
</html>