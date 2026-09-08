<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Tabla de Amortización (Cuotas)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <!-- Encabezado de información -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <div>
                    <a href="{{ route('descuentos.index', $descuento->empleado_id) }}" class="text-blue-600 hover:underline font-semibold text-sm mb-2 inline-block">← Volver a Descuentos</a>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $descuento->tipoDescuento->nombre }} - {{ $descuento->descripcion }}</h3>
                    <p class="text-gray-600">Empleado: <span class="font-bold">{{ $descuento->empleado->primer_nombre }} {{ $descuento->empleado->primer_apellido }}</span></p>
                </div>
                
                <div class="text-right bg-blue-50 p-4 rounded border border-blue-200">
                    <p class="text-sm text-gray-600">Monto Original: <span class="font-bold text-gray-900">Q. {{ number_format($descuento->monto_total, 2) }}</span></p>
                    <p class="text-lg text-gray-600 mt-1">Saldo Actual: <span class="font-bold text-red-600">Q. {{ number_format($descuento->saldo, 2) }}</span></p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabla de Cuotas -->
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-3 border-b">No. Cuota</th>
                        <th class="p-3 border-b">Proyectada para</th>
                        <th class="p-3 border-b">Monto</th>
                        <th class="p-3 border-b">Estado</th>
                        <th class="p-3 border-b">Información de Pago</th>
                        <th class="p-3 border-b text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas as $cuota)
                        <tr class="hover:bg-gray-50 {{ $cuota->pagado ? 'bg-green-50' : '' }}">
                            <td class="p-3 border-b font-bold text-gray-700 text-center">{{ $cuota->numero_cuota }}</td>
                            <td class="p-3 border-b">{{ \Carbon\Carbon::parse($cuota->fecha_cobro_proyectada)->format('d/m/Y') }}</td>
                            <td class="p-3 border-b font-bold">Q. {{ number_format($cuota->monto, 2) }}</td>
                            
                            <td class="p-3 border-b">
                                @if($cuota->pagado)
                                    <span class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pagada</span>
                                @else
                                    <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pendiente</span>
                                @endif
                            </td>

                            <td class="p-3 border-b text-xs text-gray-600">
                                @if($cuota->pagado)
                                    <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y H:i') }}</div>
                                    <div><strong>Confirmó:</strong> {{ $cuota->usuarioConfirma->name ?? 'Sistema/Desconocido' }}</div>
                                @else
                                    <span class="text-gray-400">---</span>
                                @endif
                            </td>

                            <td class="p-3 border-b text-center">
                                @if(!$cuota->pagado)
                                    <form action="{{ route('descuento-cuotas.pagar-manual', $cuota->id) }}" method="POST" onsubmit="return confirm('¿Confirmas que el empleado pagó esta cuota de Q.{{ $cuota->monto }} manualmente? (Esto descontará el saldo)')">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded shadow">
                                            Abono Manual
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">✓ Completado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">Este descuento no tiene tabla de amortización (Es un cobro recurrente).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>