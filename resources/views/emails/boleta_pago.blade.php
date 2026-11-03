<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; font-size: 12px; }
        .container { width: 100%; max-width: 700px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header-table { width: 100%; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
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
        
        <table class="header-table" style="width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
    <tr>
        <td style="vertical-align: bottom; width: 60%;">
            <img src="https://itmajadas.com/propiedades/storage/documents/4/TwpRJMpJInfGlyQghnCdyZAxuHhI8Yvztedpno8r.jpg" alt="COLAPERSA" style="max-height: 80px; width: auto; margin-bottom: 8px;">
            <h3 style="margin: 0; color: #555; font-size: 14px;">Nit: 9285719-1</h3>
            <h3 style="margin: 2px 0 0 0; color: #555; font-size: 14px;">Comprobante de Pago</h3>
        </td>
        <td style="vertical-align: bottom; width: 40%; text-align: right; line-height: 1.5;">
            <strong>Número Planilla:</strong> {{ $detalle->cargaTemporal->id ?? 1 }}<br>
            <strong>Tipo Planilla:</strong> {{ $detalle->tipo_planilla }}<br>
            <strong>Desde:</strong> {{ \Carbon\Carbon::parse($detalle->cargaTemporal->fecha_inicio)->format('d/m/Y') }} 
            <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($detalle->cargaTemporal->fecha_fin)->format('d/m/Y') }}
        </td>
    </tr>
</table>

        <table class="info-table">
            <tr>
                <td width="15%"><strong>Colaborador:</strong></td>
                <td width="35%">{{ $detalle->codigo_empleado }} - {{ $detalle->nombres }}</td>
                <td width="15%"><strong>Departamento:</strong></td>
                <td width="35%">{{ $detalle->departamento }}</td>
            </tr>
            <tr>
                <td><strong>Identificación:</strong></td>
                <td>{{ $detalle->dpi }}</td>
                <td><strong>Salario x Hora:</strong></td>
                <td>QTZ {{ number_format($detalle->salario_hora, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Puesto:</strong></td>
                <td>{{ $detalle->puesto }}</td>
                <td><strong>Forma de Pago:</strong></td>
                <td>{{ $detalle->forma_pago }}</td>
            </tr>
        </table>

        <table style="width: 100%;">
            <tr>
                <td style="width: 48%; vertical-align: top;">
                    <!-- Tabla de Ingresos -->
                    <table class="details-table">
                        <tr><th colspan="2" class="text-center">INGRESOS</th></tr>
                        <tr><td>Salario Base</td><td class="text-right">QTZ {{ number_format($detalle->salario_base, 2) }}</td></tr>
                        
                        @if($detalle->bonificacion_incentivo > 0)
                        <tr><td>Bonificación Incentivo</td><td class="text-right">QTZ {{ number_format($detalle->bonificacion_incentivo, 2) }}</td></tr>
                        @endif
                        
                        @if($detalle->horas_extras > 0)
                        <tr><td>Horas Extras ({{ $detalle->cantidad_horas }} hrs)</td><td class="text-right">QTZ {{ number_format($detalle->horas_extras, 2) }}</td></tr>
                        @endif

                         @if($detalle->horas_dobles > 0)
                        <tr><td>Horas Extras Dobles ({{ $detalle->cantidad_horas_dobles }} hrs)</td><td class="text-right">QTZ {{ number_format($detalle->horas_dobles, 2) }}</td></tr>
                        @endif

                        @if($detalle->bonificacion > 0)
                        <tr><td>Bonificaciones</td><td class="text-right">QTZ {{ number_format($detalle->bonificacion, 2) }}</td></tr>
                        @endif

                        @if($detalle->comisiones > 0)
                        <tr><td>Comisiones / Otros</td><td class="text-right">QTZ {{ number_format($detalle->comisiones, 2) }}</td></tr>
                        @endif

                        @if($detalle->retroactivos > 0)
                        <tr><td>Retroactivos</td><td class="text-right">QTZ {{ number_format($detalle->retroactivos, 2) }}</td></tr>
                        @endif

                        @php
                            $total =  $detalle->salario_base + $detalle->bonificacion_incentivo + $detalle->horas_extras + $detalle->horas_dobles + $detalle->bonificacion + $detalle->comisiones + $detalle->retroactivos;
                        @endphp
                        <tr class="total-row"><td>Total Ingresos:</td><td class="text-right">QTZ {{ number_format($total, 2) }}</td></tr>
                    </table>
                </td>
                <td style="width: 4%;"></td> <!-- Espacio -->
                <td style="width: 48%; vertical-align: top;">
                    <!-- Tabla de Deducciones -->
                    <table class="details-table">
                        <tr><th colspan="2" class="text-center">DEDUCCIONES</th></tr>
                        <tr><td>IGSS Laboral</td><td class="text-right text-danger">QTZ {{ number_format($detalle->igss_laboral, 2) }}</td></tr>
                        
                        @if($detalle->isr > 0)
                        <tr><td>ISR</td><td class="text-right">QTZ {{ number_format($detalle->isr, 2) }}</td></tr>
                        @endif

                        @php $sumaAnticipos = $detalle->anticipos_salario_1 + $detalle->anticipos_salario_2; @endphp
                        @if($sumaAnticipos > 0)
                        <tr><td>Anticipos de Salario</td><td class="text-right">QTZ {{ number_format($sumaAnticipos, 2) }}</td></tr>
                        @endif

                        @if($detalle->otros_descuentos > 0)
                        <tr><td>Otros Descuentos</td><td class="text-right">QTZ {{ number_format($detalle->otros_descuentos, 2) }}</td></tr>
                        @endif

                        <tr class="total-row"><td>Total Deducciones:</td><td class="text-right">QTZ {{ number_format($detalle->total_deducciones, 2) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Detalle de horas -->
        <table class="details-table" style="margin-top: 10px;">
            <tr>
                <th class="text-center">Horas Normales</th>
                <th class="text-center">Horas Extras</th>
                <th class="text-center">Horas Dobles</th>
                <th class="text-center">Total de Horas</th>
            </tr>
            <tr class="text-center">
                <td>{{ number_format($detalle->cantidad_horas_global, 2) }}</td>
                <td>{{ number_format($detalle->cantidad_horas, 2) }}</td>
                <td>{{ number_format($detalle->cantidad_horas_dobles, 2) }}</td>
                <td><strong>{{ number_format($detalle->cantidad_horas_global + $detalle->cantidad_horas + $detalle->cantidad_horas_dobles, 2) }}</strong></td>
            </tr>
        </table>

        <div class="neto">
            Pago Neto: QTZ {{ number_format($total - $detalle->total_deducciones, 2) }}
        </div>
        
        <p style="text-align: center; color: #777; font-size: 10px; margin-top: 30px;">
            Este es un comprobante electrónico generado automáticamente.
        </p>
    </div>
</body>
</html>