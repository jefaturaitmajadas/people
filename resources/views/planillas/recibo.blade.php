<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 hidden-print text-right mb-4">
        <a href="{{ route('planillas.show', $detalle->planilla_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow mr-2">
            ← Volver a Planilla
        </a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
            🖨️ Imprimir Recibo
        </button>
    </div>

    <!-- Contenedor del Recibo -->
    <div class="recibo-impresion bg-white shadow-xl mx-auto text-gray-900 border border-gray-300">
        
        <!-- Encabezado -->
        <div class="flex justify-between items-center border-b-2 border-gray-800 pb-4 mb-4">
            <div>
                <h1 class="text-xl font-black uppercase">{{ $detalle->planilla->empresa->razon_social ?? $detalle->planilla->empresa->nombre }}</h1>
                <p class="text-xs text-gray-600">NIT: {{ $detalle->planilla->empresa->nit }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-bold uppercase text-indigo-800">Recibo de Pago de Salario</h2>
                <p class="text-xs font-bold mt-1">Período: {{ \Carbon\Carbon::parse($detalle->planilla->fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($detalle->planilla->fecha_fin)->format('d/m/Y') }}</p>
                <p class="text-xs text-gray-500">Planilla Ref: {{ $detalle->planilla->codigo }}</p>
            </div>
        </div>

        <!-- Datos del Empleado -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm border border-gray-200 p-3 rounded bg-gray-50">
            <div>
                <p><span class="font-bold">Colaborador:</span> {{ mb_strtoupper($detalle->empleado->primer_nombre . ' ' . $detalle->empleado->primer_apellido) }}</p>
                <p><span class="font-bold">DPI:</span> {{ $detalle->empleado->identificacion }}</p>
                <p><span class="font-bold">Afiliación IGSS:</span> {{ $detalle->empleado->no_igss ?? 'N/A' }}</p>
            </div>
            <div>
                <p><span class="font-bold">Puesto:</span> {{ mb_strtoupper($detalle->empleado->puesto->nombre ?? 'N/A') }}</p>
                <p><span class="font-bold">Salario Base Mensual:</span> Q. {{ number_format($detalle->salario_base_mes, 2) }}</p>
                <p><span class="font-bold">Días Laborados:</span> {{ $detalle->dias_trabajados }} días</p>
            </div>
        </div>

        <!-- Desglose de Ingresos y Egresos -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            
            <!-- Columna de Ingresos -->
            <div>
                <h3 class="font-bold uppercase text-xs bg-gray-200 p-2 text-center border-b border-gray-400">Ingresos Devengados</h3>
                <table class="w-full text-xs mt-2">
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Salario Proporcional</td>
                            <td class="py-1 text-right">Q. {{ number_format($detalle->salario_proporcional, 2) }}</td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Bonificación Decreto 37-2001</td>
                            <td class="py-1 text-right">Q. {{ number_format($detalle->bono_decreto, 2) }}</td>
                        </tr>
                        
                        @if($detalle->total_horas_extras > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Horas Extras</td>
                            <td class="py-1 text-right">Q. {{ number_format($detalle->total_horas_extras, 2) }}</td>
                        </tr>
                        @endif

                        @if($detalle->total_ingresos_adicionales > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Otros Ingresos / Comisiones</td>
                            <td class="py-1 text-right">Q. {{ number_format($detalle->total_ingresos_adicionales, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Columna de Egresos (Deducciones) -->
            <div>
                <h3 class="font-bold uppercase text-xs bg-gray-200 p-2 text-center border-b border-gray-400">Deducciones</h3>
                <table class="w-full text-xs mt-2">
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Cuota Laboral IGSS (4.83%)</td>
                            <td class="py-1 text-right text-red-600">Q. {{ number_format($detalle->igss_laboral, 2) }}</td>
                        </tr>
                        
                        @if($detalle->isr > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Retención ISR</td>
                            <td class="py-1 text-right text-red-600">Q. {{ number_format($detalle->isr, 2) }}</td>
                        </tr>
                        @endif

                        <!-- AQUÍ AGREGAMOS EL ANTICIPO DE SALARIO -->
                        @if($detalle->pago_prestamos > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-800">Anticipo de Salario</td>
                            <td class="py-1 text-right text-red-600">Q. {{ number_format($detalle->pago_prestamos, 2) }}</td>
                        </tr>
                        @endif

                        @php
                            // Calculamos si hay otras deducciones quitando el ISR y Préstamos del total_descuentos
                            $otrosDescuentos = $detalle->total_descuentos - $detalle->isr - $detalle->pago_prestamos;
                        @endphp

                        @if($otrosDescuentos > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-1 text-gray-700">Anticipos de Salario</td>
                            <td class="py-1 text-right text-red-600">Q. {{ number_format($otrosDescuentos, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Totales -->
        @php
            $sumaIngresos = $detalle->salario_proporcional + $detalle->bono_decreto + $detalle->total_horas_extras + $detalle->total_ingresos_adicionales;
            $sumaEgresos = $detalle->igss_laboral + $detalle->total_descuentos;
        @endphp

        <div class="grid grid-cols-2 gap-6 border-t-2 border-gray-800 pt-2 mb-12">
            <div class="text-right flex justify-between font-bold text-sm">
                <span>Total Ingresos:</span>
                <span>Q. {{ number_format($sumaIngresos, 2) }}</span>
            </div>
            <div class="text-right flex justify-between font-bold text-sm">
                <span>Total Deducciones:</span>
                <span class="text-red-600">Q. {{ number_format($sumaEgresos, 2) }}</span>
            </div>
        </div>

        <div class="bg-gray-100 p-4 text-center rounded border border-gray-300 mb-16">
            <p class="text-sm uppercase text-gray-700">Líquido a Recibir</p>
            <p class="text-2xl font-black text-gray-900">Q. {{ number_format($detalle->liquido_a_recibir, 2) }}</p>
        </div>

        <!-- Firmas -->
        <div class="flex justify-around text-center pt-8 border-t border-transparent text-xs">
            <div class="w-48 border-t border-gray-600 pt-2">
                <p>Recibí Conforme</p>
                <p class="font-bold uppercase mt-1">{{ $detalle->empleado->primer_nombre }} {{ $detalle->empleado->primer_apellido }}</p>
            </div>
            <div class="w-48 border-t border-gray-600 pt-2">
                <p>Firma / Sello Empresa</p>
                <p class="font-bold uppercase mt-1">{{ $detalle->planilla->empresa->razon_social ?? $detalle->planilla->empresa->nombre }}</p>
            </div>
        </div>

    </div>

    <!-- Estilos de Impresión -->
    <style>
        .recibo-impresion { width: 100%; max-width: 21.5cm; padding: 1.5cm; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        @media print { 
            body * { visibility: hidden; } 
            .recibo-impresion, .recibo-impresion * { visibility: visible; } 
            .recibo-impresion { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; border: none !important; } 
            .hidden-print { display: none !important; } 
        }
    </style>
</x-app-layout>