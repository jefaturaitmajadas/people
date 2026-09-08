<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Control de Horas Extras: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver a Empleados</a>
                <a href="{{ route('horas-extras.create', $empleado->id) }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Reportar Horas Extras
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <table class="w-full text-left border-collapse mt-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border-b">Mes Reportado</th>
                        <th class="p-3 border-b">Desglose (Horas x Tarifa)</th>
                        <th class="p-3 border-b">Total a Pagar</th>
                        <th class="p-3 border-b">Ingresado por</th>
                        <th class="p-3 border-b">Estado Planilla</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($horas as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-bold">{{ \Carbon\Carbon::parse($item->fecha_presentacion)->format('M Y') }}</td>
                            <td class="p-3 border-b text-sm">
                                {{ $item->cantidad_horas }} hrs a Q.{{ number_format($item->salario_por_hora_sugerido, 2) }}<br>
                                <span class="text-xs text-gray-500">Factor: x{{ $item->factor_multiplicador }} ({{ $item->factor_multiplicador == 1.5 ? 'Ordinarias' : 'Doble/Asueto' }})</span>
                            </td>
                            <td class="p-3 border-b font-bold text-indigo-700">Q. {{ number_format($item->total_pagar, 2) }}</td>
                            <td class="p-3 border-b text-xs text-gray-600">{{ $item->usuarioCreador->name ?? 'Desconocido' }}</td>
                            <td class="p-3 border-b">
                                @if($item->pagado)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Pagado</span>
                                    <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($item->fecha_pagada)->format('d/m/Y') }}</div>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Pendiente</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center">
                                @if(!$item->pagado)
                                    <form action="{{ route('horas-extras.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este reporte?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">X Anular</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">No hay reporte de horas extras para este empleado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>