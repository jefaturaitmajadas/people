<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Cumpleañeros del Mes
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Panel de Acciones -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 hidden-print">
            @include('cumpleanos._filtros', ['ruta' => 'reportes.cumpleanos'])

            <div class="flex gap-2">
                <a href="{{ route('reportes.cumpleanos.arte', ['mes' => $mesSeleccionado, 'anio' => $anioSeleccionado]) }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    🎨 Ver Arte
                </a>
                <button type="button" onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    🖨️ Imprimir
                </button>
            </div>
        </div>

        <!-- Área del Reporte Imprimible -->
        <div class="area-reporte bg-white p-8 rounded-lg shadow-xl text-gray-900">

            <div class="text-center mb-8 border-b-2 border-gray-800 pb-4">
                <h1 class="text-2xl font-black uppercase">{{ $empresa->razon_social ?? $empresa->nombre }}</h1>
                <h2 class="text-lg font-bold mt-1 uppercase text-gray-700">Cumpleañeros del Mes</h2>
                <p class="font-bold text-indigo-700 mt-2 text-xl uppercase">{{ $meses[$mesSeleccionado] }} {{ $anioSeleccionado }}</p>
            </div>

            <div class="mb-6 bg-gray-50 p-4 border rounded">
                <p class="text-sm font-bold">Total de cumpleañeros: <span class="text-indigo-600 text-lg">{{ $empleados->count() }}</span></p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-800 uppercase text-xs">
                            <th class="p-3 border border-gray-300 font-bold text-center">Día</th>
                            <th class="p-3 border border-gray-300 font-bold">Cód. Emp.</th>
                            <th class="p-3 border border-gray-300 font-bold">Colaborador</th>
                            <th class="p-3 border border-gray-300 font-bold">Departamento</th>
                            <th class="p-3 border border-gray-300 font-bold">Puesto</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Fecha Nacimiento</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Cumple</th>
                            <th class="p-3 border border-gray-300 font-bold">Correo de Felicitación</th>
                            <th class="p-3 border border-gray-300 font-bold text-center hidden-print">Correo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $empleado)
                            @php
                                $nacimiento = \Carbon\Carbon::parse($empleado->fecha_nacimiento);
                                $edad = $anioSeleccionado - $nacimiento->year;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-2 border border-gray-300 text-center font-black text-indigo-700">{{ $nacimiento->format('d') }}</td>
                                <td class="p-2 border border-gray-300 text-center font-bold text-gray-600">{{ $empleado->codigo_empleado ?? $empleado->id }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->nombre_completo }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->departamento->nombre ?? 'N/A' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->puesto->nombre ?? 'N/A' }}</td>
                                <td class="p-2 border border-gray-300 text-center">{{ $nacimiento->format('d/m/Y') }}</td>
                                <td class="p-2 border border-gray-300 text-center font-bold">{{ $edad > 0 ? $edad . ' años' : '-' }}</td>
                                <td class="p-2 border border-gray-300">
                                    @if($empleado->correo_notificacion)
                                        {{ $empleado->correo_notificacion }}
                                    @else
                                        <span class="text-red-600 font-bold text-xs">Sin correo (no se envía)</span>
                                    @endif
                                </td>
                                <td class="p-2 border border-gray-300 text-center hidden-print">
                                    <a href="{{ route('empleados.cumpleanos.correo', $empleado) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs">Vista previa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-6 text-center text-gray-500 font-bold border border-gray-300">
                                    No hay cumpleañeros registrados en {{ $meses[$mesSeleccionado] }} {{ $anioSeleccionado }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .hidden-print, nav, aside, header { display: none !important; }
            .area-reporte { box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</x-app-layout>
