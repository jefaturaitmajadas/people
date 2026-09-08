<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Incidencias y Suspensiones: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver a Empleados</a>
                <a href="{{ route('incidencias.create', $empleado->id) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Registrar Incidencia
                </a>
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

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border-b">Tipo / Observaciones</th>
                        <th class="p-3 border-b text-center">Fechas / Días</th>
                        <th class="p-3 border-b text-center">Documento</th>
                        <th class="p-3 border-b">Estado Planilla</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidencias as $incidencia)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">
                                <span class="font-bold text-gray-700">{{ $incidencia->tipoIncidencia->nombre ?? 'N/A' }}</span><br>
                                @if($incidencia->tipoIncidencia->descuenta_salario)
                                    <span class="text-xs font-semibold text-red-600">Descuenta Salario</span>
                                @else
                                    <span class="text-xs font-semibold text-blue-600">Con Goce</span>
                                @endif
                                @if($incidencia->observaciones)
                                    <br><span class="text-sm text-gray-500">{{ $incidencia->observaciones }}</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center">
                                <span class="text-gray-700 text-sm">
                                    {{ \Carbon\Carbon::parse($incidencia->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($incidencia->fecha_fin)->format('d/m/Y') }}
                                </span><br>
                                <span class="font-bold text-gray-800 text-sm">
                                    {{ $incidencia->cantidad_dias }} {{ $incidencia->cantidad_dias == 1 ? 'día' : 'días' }}
                                </span>
                            </td>
                            <td class="p-3 border-b text-center">
                                @if($incidencia->documento_adjunto)
                                    <a href="{{ asset('storage/' . $incidencia->documento_adjunto) }}" target="_blank" class="text-teal-600 hover:text-teal-900 font-bold text-sm">
                                        Ver Adjunto
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                @if($incidencia->aplicado_en_planilla)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Procesado</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pendiente</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center flex justify-center gap-3 items-center h-full mt-2">
                                @if(!$incidencia->aplicado_en_planilla)
                                    <a href="{{ route('incidencias.edit', $incidencia->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                    
                                    <form action="{{ route('incidencias.destroy', $incidencia->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta incidencia?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">Bloqueado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No hay incidencias registradas para este empleado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-4">
                {{ $incidencias->links() }}
            </div>
        </div>
    </div>
</x-app-layout>