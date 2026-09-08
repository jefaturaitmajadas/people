<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 hidden-print text-right mb-4">
        <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
            🖨️ Imprimir Estado de Cuenta
        </button>
    </div>

    <div class="hoja-carta bg-white shadow-xl mx-auto text-gray-900">
        
        <!-- Encabezado -->
        <div class="flex justify-between items-start border-b-2 border-gray-800 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-black uppercase">{{ $empresa->razon_social ?? $empresa->nombre }}</h1>
                <p class="text-sm text-gray-600">Departamento de Recursos Humanos</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold uppercase text-indigo-800">Estado de Cuenta</h2>
                <p class="text-sm font-bold mt-1">Fecha de Emisión: {{ now()->format('d/m/Y h:i A') }}</p>
            </div>
        </div>

        <!-- Datos del Préstamo -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <h3 class="font-bold border-b border-gray-300 pb-2 mb-2 uppercase text-sm">Datos del Colaborador</h3>
                <p class="text-sm"><span class="font-bold">Nombre:</span> {{ mb_strtoupper($empleado->primer_nombre . ' ' . $empleado->primer_apellido) }}</p>
                <p class="text-sm"><span class="font-bold">DPI:</span> {{ $empleado->identificacion }}</p>
                <p class="text-sm"><span class="font-bold">Puesto:</span> {{ mb_strtoupper($empleado->puesto->nombre ?? 'N/A') }}</p>
            </div>
            
            <div class="bg-indigo-50 p-4 rounded border border-indigo-200">
                <h3 class="font-bold text-indigo-900 border-b border-indigo-200 pb-2 mb-2 uppercase text-sm">Resumen del Crédito</h3>
                <div class="grid grid-cols-2 text-sm gap-2">
                    <p><span class="font-bold text-indigo-700">Monto Total:</span><br> Q. {{ number_format($prestamo->monto_total, 2) }}</p>
                    <p><span class="font-bold text-green-700">Total Abonado:</span><br> Q. {{ number_format($totalAbonado, 2) }}</p>
                    <p class="col-span-2 text-lg mt-2 pt-2 border-t border-indigo-200">
                        <span class="font-black text-red-700">Saldo Pendiente:</span> 
                        <span class="font-black">Q. {{ number_format($saldoPendiente, 2) }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Estado de Cuotas -->
        <h3 class="font-bold uppercase text-sm mb-4">Detalle de Movimientos y Cuotas</h3>
        <table class="w-full text-center text-xs border-collapse border border-gray-300 mb-8">
            <thead class="bg-gray-200 text-gray-800 uppercase">
                <tr>
                    <th class="p-2 border border-gray-300">No.</th>
                    <th class="p-2 border border-gray-300">Fecha Prog.</th>
                    <th class="p-2 border border-gray-300">Capital</th>
                    <th class="p-2 border border-gray-300">Ahorro</th>
                    <th class="p-2 border border-gray-300">Cuota Total</th>
                    <th class="p-2 border border-gray-300">Estado</th>
                    <th class="p-2 border border-gray-300">Fecha de Pago</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cuotas as $cuota)
                <tr class="{{ $cuota->pagado ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                    <td class="p-2 border border-gray-300">{{ $cuota->numero_cuota }} / {{ $prestamo->cantidad_cuotas }}</td>
                    <td class="p-2 border border-gray-300">{{ \Carbon\Carbon::parse($cuota->fecha_aplicacion)->format('d/m/Y') }}</td>
                    <td class="p-2 border border-gray-300">Q. {{ number_format($cuota->monto_capital, 2) }}</td>
                    <td class="p-2 border border-gray-300">Q. {{ number_format($cuota->monto_ahorro, 2) }}</td>
                    <td class="p-2 border border-gray-300 font-bold">Q. {{ number_format($cuota->monto_total, 2) }}</td>
                    <td class="p-2 border border-gray-300 font-bold">
                        @if($cuota->pagado)
                            <span class="text-green-700 uppercase">Pagado</span>
                        @else
                            <span class="text-amber-600 uppercase">Pendiente</span>
                        @endif
                    </td>
                    <td class="p-2 border border-gray-300 text-gray-600">
                        {{ $cuota->fecha_pagado ? \Carbon\Carbon::parse($cuota->fecha_pagado)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center mt-12 text-xs text-gray-500">
            <p>Documento generado automáticamente por el Sistema de Recursos Humanos.</p>
            <p>Estado del Préstamo: <strong>{{ mb_strtoupper($prestamo->estado) }}</strong></p>
        </div>

    </div>

    <!-- Estilos de Impresión -->
    <style>
        .hoja-carta { width: 21.59cm; min-height: 27.94cm; padding: 2cm 2.5cm; box-sizing: border-box; background: white; font-family: 'Arial', sans-serif; }
        @media print { body * { visibility: hidden; } .hoja-carta, .hoja-carta * { visibility: visible; } .hoja-carta { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; } .hidden-print { display: none !important; } }
    </style>
</x-app-layout>