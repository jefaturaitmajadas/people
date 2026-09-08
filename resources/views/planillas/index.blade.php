<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Historial de Planillas (Nómina)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Registro de Nóminas</h3>
                    <p class="text-sm text-gray-500">Historial completo de pagos a empleados.</p>
                </div>
                <a href="{{ route('planillas.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Generar Nueva Planilla
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border-b">Código / Descripción</th>
                        <th class="p-3 border-b">Período</th>
                        <th class="p-3 border-b">Total Nómina</th>
                        <th class="p-3 border-b">Estado</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($planillas as $planilla)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">
                                <span class="font-bold text-gray-800">{{ $planilla->codigo }}</span><br>
                                <span class="text-sm text-gray-600">{{ $planilla->descripcion }}</span>
                            </td>
                            <td class="p-3 border-b text-sm">
                                Del: {{ \Carbon\Carbon::parse($planilla->fecha_inicio)->format('d/m/Y') }}<br>
                                Al: {{ \Carbon\Carbon::parse($planilla->fecha_fin)->format('d/m/Y') }}<br>
                                <span class="text-xs text-gray-400 capitalize">{{ $planilla->tipo_periodo }}</span>
                            </td>
                            <td class="p-3 border-b font-bold text-indigo-700">
                                Q. {{ number_format($planilla->total_nomina, 2) }}
                            </td>
                            <td class="p-3 border-b">
                                @if($planilla->estado == 'borrador')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Borrador</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Cerrada ✓</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center space-x-2">
                                <a href="{{ route('planillas.show', $planilla->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">Ver Detalles</a>
                                
                                {{-- 
                                    AQUÍ INICIA EL BOTÓN DE ARCHIVO DE BANCOS
                                    TODO: Descomentar la condición de estado 'cerrada' cuando termine la etapa de pruebas y demostración.
                                --}}
                                {{-- @if($planilla->estado == 'cerrada') --}}
                                    
                                    <a href="{{ route('planillas.bancos', $planilla->id) }}" class="text-green-600 hover:text-green-900 font-bold ml-2 text-sm border border-green-600 px-2 py-1 rounded hover:bg-green-50 transition-colors">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Archivo Bancos
                                    </a>

                                    <a href="{{ route('planillas.igss', $planilla->id) }}" class="text-blue-600 hover:text-blue-900 font-bold ml-2 text-sm border border-blue-600 px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Reporte IGSS
                                    </a>
                                    
                                {{-- @endif --}}
                                @if($planilla->estado == 'borrador')
                                    <form action="{{ route('planillas.destroy', $planilla->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este borrador? No afectará saldos de empleados.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold ml-2">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No hay planillas generadas aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>