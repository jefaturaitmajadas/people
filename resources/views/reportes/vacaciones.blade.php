<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Saldos de Vacaciones del Personal
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Panel de Acciones -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $empresa->nombre }}</h3>
                <p class="text-sm text-gray-500">Reporte de pasivo vacacional (Empleados Activos)</p>
            </div>
            
            <!-- Botón para descargar el Excel -->
            <a href="{{ route('reportes.vacaciones.excel') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow transition-colors inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Exportar a Excel
            </a>
        </div>

        <!-- Tabla de Datos -->
        <div class="bg-white p-6 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-800 uppercase text-xs">
                            <th class="p-3 border border-gray-300 font-bold">Cód. Emp.</th>
                            <th class="p-3 border border-gray-300 font-bold">Primer Nombre</th>
                            <th class="p-3 border border-gray-300 font-bold">Segundo Nombre</th>
                            <th class="p-3 border border-gray-300 font-bold">Primer Apellido</th>
                            <th class="p-3 border border-gray-300 font-bold">Segundo Apellido</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Días Disponibles</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Días Con Goze de Salario</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Acumulado</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Días Utilizados</th>
                            <th class="p-3 border border-gray-300 font-bold text-center">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $empleado)
                            @php
                                $calculo = $empleado->calcularSaldoVacaciones();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-2 border border-gray-300 text-center font-bold text-gray-600">
                                    {{ $empleado->codigo_empleado ?? $empleado->id }}
                                </td>
                                <td class="p-2 border border-gray-300">{{ $empleado->primer_nombre }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->segundo_nombre }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->primer_apellido }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->segundo_apellido }}</td>
                                
                                <td class="p-2 border border-gray-300 text-center font-black {{ $calculo['saldo_disponible'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $calculo['dias_ley'] }}
                                </td>
                                 <td class="p-2 border border-gray-300 text-center font-black {{ $calculo['saldo_disponible'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $calculo['dias_goze'] }}
                                </td>
                                <td class="p-2 border border-gray-300 text-center font-black {{ $calculo['saldo_disponible'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $calculo['dias_acumulados'] }}
                                </td>
                                
                                <td class="p-2 border border-gray-300 text-center font-bold text-gray-700">
                                    {{ $calculo['dias_tomados'] }}
                                </td>
                                <td class="p-2 border border-gray-300 text-center font-black {{ $calculo['saldo_disponible'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $calculo['saldo_disponible'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-500 font-bold border border-gray-300">
                                    No hay empleados activos registrados para este reporte.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>