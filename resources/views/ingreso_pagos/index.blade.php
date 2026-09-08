<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Detalle de Pagos Proyectados (Ingreso)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <!-- Encabezado de información -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <div>
                    <a href="{{ route('ingresos.index', $ingreso->empleado_id) }}" class="text-teal-600 hover:underline font-semibold text-sm mb-2 inline-block">← Volver a Ingresos</a>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $ingreso->tipoIngreso->nombre }} - {{ $ingreso->descripcion }}</h3>
                    <p class="text-gray-600">Empleado: <span class="font-bold">{{ $ingreso->empleado->primer_nombre }} {{ $ingreso->empleado->primer_apellido }}</span></p>
                </div>
                
                <div class="text-right bg-green-50 p-4 rounded border border-green-200">
                    <p class="text-sm text-gray-600">Monto Total del Bono/Comisión:</p>
                    <p class="text-xl text-green-700 font-bold">Q. {{ number_format($ingreso->monto_total, 2) }}</p>
                </div>
            </div>

            <!-- Tabla de Pagos -->
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-3 border-b">No. Pago</th>
                        <th class="p-3 border-b">Proyectado para</th>
                        <th class="p-3 border-b">Monto a Pagar</th>
                        <th class="p-3 border-b">Estado en Planilla</th>
                        <th class="p-3 border-b">Auditoría (Cierre)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                        <tr class="hover:bg-gray-50 {{ $pago->pagado ? 'bg-green-50' : '' }}">
                            <td class="p-3 border-b font-bold text-gray-700 text-center">{{ $pago->numero_cuota }}</td>
                            <td class="p-3 border-b">{{ \Carbon\Carbon::parse($pago->fecha_pago_proyectada)->format('d/m/Y') }}</td>
                            <td class="p-3 border-b font-bold text-green-700">Q. {{ number_format($pago->monto, 2) }}</td>
                            
                            <td class="p-3 border-b">
                                @if($pago->pagado)
                                    <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">✓ Acreditado</span>
                                @else
                                    <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pendiente de Planilla</span>
                                @endif
                            </td>

                            <td class="p-3 border-b text-xs text-gray-600">
                                @if($pago->pagado)
                                    <div><strong>Fecha Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</div>
                                    <div><strong>Autorizó:</strong> {{ $pago->usuarioConfirma->name ?? 'Sistema' }}</div>
                                @else
                                    <span class="text-gray-400">Esperando cierre de nómina...</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">Este ingreso no tiene pagos proyectados (probablemente es un registro antiguo o recurrente).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>