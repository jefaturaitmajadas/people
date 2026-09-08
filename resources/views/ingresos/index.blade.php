<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Ingresos Adicionales: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver a Empleados</a>
                <a href="{{ route('ingresos.create', $empleado->id) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Registrar Ingreso
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border-b">Tipo / Descripción</th>
                        <th class="p-3 border-b">Monto a sumar</th>
                        <th class="p-3 border-b">Modalidad</th>
                        <th class="p-3 border-b">Estado</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingresos as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">
                                <span class="font-bold text-gray-700">{{ $item->tipoIngreso->nombre ?? 'N/A' }}</span><br>
                                <span class="text-sm text-gray-500">{{ $item->descripcion }}</span>
                            </td>
                            <td class="p-3 border-b font-bold text-green-700">Q. {{ number_format($item->monto_cuota, 2) }}</td>
                            <td class="p-3 border-b">
                                @if($item->es_recurrente)
                                    <span class="text-gray-500 text-sm">Recurrente</span>
                                @else
                                    <span class="text-gray-700 text-sm">Pago Único / Cuotas</span><br>
                                    <span class="text-xs text-gray-400">Total: Q.{{ number_format($item->monto_total, 2) }}</span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                @if($item->estado)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Activo</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">Finalizado</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center flex justify-center gap-3">
                                @if(!$item->es_recurrente)
                                    <a href="{{ route('ingreso-pagos.index', $item->id) }}" class="text-teal-600 hover:text-teal-900 font-bold">Ver Pagos</a>
                                @endif
                                <a href="{{ route('ingresos.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No hay ingresos adicionales registrados para este empleado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>