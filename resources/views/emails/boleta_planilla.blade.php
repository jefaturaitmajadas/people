@php
    $planilla = $detalle->planilla;
    $empleado = $detalle->empleado;
    $empresa  = $planilla->empresa;

    $nombre = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
        $empleado->primer_nombre ?? null, $empleado->segundo_nombre ?? null,
        $empleado->primer_apellido ?? null, $empleado->segundo_apellido ?? null,
    ]))));

    $salario      = (float) $detalle->salario_proporcional;
    $bono         = (float) $detalle->bono_decreto;
    $horasExtras  = (float) $detalle->total_horas_extras;
    $ingresosAd   = (float) $detalle->total_ingresos_adicionales;
    $totalIngresos = $salario + $bono + $horasExtras + $ingresosAd;

    $igss      = (float) $detalle->igss_laboral;
    $isr       = (float) $detalle->isr;
    $prestamos = (float) $detalle->pago_prestamos;
    $otros     = round((float) $detalle->total_descuentos - $isr - $prestamos, 2);
    $totalDeducciones = $igss + $isr + $prestamos + $otros;

    $nombrePlanilla = $planilla->tipo_periodo == 'quincenal' ? 'Planilla Quincenal Operativa' : 'Planilla Mensual Administrativa';
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; font-size: 12px; }
        .container { width: 100%; max-width: 700px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details-table td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .neto { font-size: 16px; font-weight: bold; text-align: right; margin-top: 20px; border-top: 2px solid #000; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">

        <table style="width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
            <tr>
                <td style="vertical-align: bottom; width: 60%;">
                    <img src="https://itmajadas.com/propiedades/storage/documents/4/TwpRJMpJInfGlyQghnCdyZAxuHhI8Yvztedpno8r.jpg" alt="{{ $empresa->nombre ?? 'COLAPERSA' }}" style="max-height: 80px; width: auto; margin-bottom: 8px;">
                    <h3 style="margin: 0; color: #555; font-size: 14px;">Nit: {{ $empresa->nit ?? '' }}</h3>
                    <h3 style="margin: 2px 0 0 0; color: #555; font-size: 14px;">Comprobante de Pago</h3>
                </td>
                <td style="vertical-align: bottom; width: 40%; text-align: right; line-height: 1.5;">
                    <strong>Planilla:</strong> {{ $planilla->codigo }}<br>
                    <strong>Tipo Planilla:</strong> {{ $nombrePlanilla }}<br>
                    <strong>Descripción:</strong> {{ $planilla->descripcion }}<br>
                    <strong>Desde:</strong> {{ \Carbon\Carbon::parse($planilla->fecha_inicio)->format('d/m/Y') }}
                    <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($planilla->fecha_fin)->format('d/m/Y') }}
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="15%"><strong>Colaborador:</strong></td>
                <td width="35%">{{ $empleado->codigo_empleado ?? '' }} - {{ $nombre }}</td>
                <td width="15%"><strong>Departamento:</strong></td>
                <td width="35%">{{ $empleado->departamento->nombre ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Identificación:</strong></td>
                <td>{{ $empleado->identificacion ?? '' }}</td>
                <td><strong>Días Trabajados:</strong></td>
                <td>{{ $detalle->dias_trabajados }}</td>
            </tr>
            <tr>
                <td><strong>Puesto:</strong></td>
                <td>{{ $empleado->puesto->nombre ?? '' }}</td>
                <td><strong>Salario Mensual:</strong></td>
                <td>QTZ {{ number_format($detalle->salario_base_mes, 2) }}</td>
            </tr>
        </table>

        <table style="width: 100%;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <table class="details-table">
                        <tr><th colspan="2" class="text-center">INGRESOS</th></tr>
                        <tr><td>Salario Devengado</td><td class="text-right">QTZ {{ number_format($salario, 2) }}</td></tr>

                        @if($bono > 0)
                        <tr><td>Bonificación Incentivo</td><td class="text-right">QTZ {{ number_format($bono, 2) }}</td></tr>
                        @endif

                        @if($horasExtras > 0)
                        <tr><td>Horas Extras</td><td class="text-right">QTZ {{ number_format($horasExtras, 2) }}</td></tr>
                        @endif

                        @if($ingresosAd > 0)
                        <tr><td>Otros Ingresos</td><td class="text-right">QTZ {{ number_format($ingresosAd, 2) }}</td></tr>
                        @endif

                        <tr class="total-row"><td>Total Ingresos:</td><td class="text-right">QTZ {{ number_format($totalIngresos, 2) }}</td></tr>
                    </table>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%; vertical-align: top;">
                    <table class="details-table">
                        <tr><th colspan="2" class="text-center">DEDUCCIONES</th></tr>
                        <tr><td>IGSS Laboral</td><td class="text-right">QTZ {{ number_format($igss, 2) }}</td></tr>

                        @if($isr > 0)
                        <tr><td>ISR</td><td class="text-right">QTZ {{ number_format($isr, 2) }}</td></tr>
                        @endif

                        @if($prestamos > 0)
                        <tr><td>Préstamos</td><td class="text-right">QTZ {{ number_format($prestamos, 2) }}</td></tr>
                        @endif

                        @if($otros > 0)
                        <tr><td>Otros Descuentos</td><td class="text-right">QTZ {{ number_format($otros, 2) }}</td></tr>
                        @endif

                        <tr class="total-row"><td>Total Deducciones:</td><td class="text-right">QTZ {{ number_format($totalDeducciones, 2) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="neto">
            Pago Neto: QTZ {{ number_format($detalle->liquido_a_recibir, 2) }}
        </div>

        <p style="text-align: center; color: #777; font-size: 10px; margin-top: 30px;">
            Este es un comprobante electrónico generado automáticamente.
        </p>
    </div>
</body>
</html>
