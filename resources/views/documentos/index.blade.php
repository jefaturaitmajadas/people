<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Documentos de: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver a Empleados</a>
                <a href="{{ route('documentos.create', $empleado->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Añadir Documento
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
                        <th class="p-3 border-b">Tipo de Documento</th>
                        <th class="p-3 border-b">Descripción</th>
                        <th class="p-3 border-b">Emisión</th>
                        <th class="p-3 border-b">Vencimiento</th>
                        <th class="p-3 border-b text-center">Adjunto</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-medium">{{ $item->tipoDocumento->nombre ?? 'N/A' }}</td>
                            <td class="p-3 border-b">{{ $item->descripcion }}</td>
                            <td class="p-3 border-b">{{ \Carbon\Carbon::parse($item->fecha_emision)->format('d/m/Y') }}</td>
                            <td class="p-3 border-b">
                                {{ $item->fecha_vencimiento ? \Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <!-- En el TBODY (dentro del foreach) -->
                            <td class="p-3 border-b text-center">
                                @if($item->archivo_ruta)
                                    <a href="{{ asset('storage/' . $item->archivo_ruta) }}" target="_blank" class="text-blue-500 hover:text-blue-700 font-bold text-sm" title="Ver Documento">
                                        Ver 📄
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Sin archivo</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center flex justify-center gap-3">
                                <a href="{{ route('documentos.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                <form action="{{ route('documentos.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este documento?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No hay documentos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>