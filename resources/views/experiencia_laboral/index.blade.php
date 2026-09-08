<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Experiencia Laboral: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline">← Volver al Directorio</a>
                <a href="{{ route('experiencia-laboral.create', $empleado->id) }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded shadow">
                    + Añadir Experiencia
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border-b">Empresa</th>
                        <th class="p-3 border-b">Puesto</th>
                        <th class="p-3 border-b">Periodo</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($experiencias as $exp)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-medium">{{ $exp->empresa }}<br><span class="text-xs text-gray-500">{{ $exp->telefono_empresa }}</span></td>
                            <td class="p-3 border-b">{{ $exp->puesto }}</td>
                            <td class="p-3 border-b text-sm">
                                {{ \Carbon\Carbon::parse($exp->fecha_inicio)->format('d/m/Y') }} al 
                                {{ $exp->fecha_fin ? \Carbon\Carbon::parse($exp->fecha_fin)->format('d/m/Y') : 'Actualidad' }}
                            </td>
                            <td class="p-3 border-b text-center flex justify-center gap-3">
                                <a href="{{ route('experiencia-laboral.edit', $exp->id) }}" class="text-amber-600 font-bold">Editar</a>
                                <form action="{{ route('experiencia-laboral.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('¿Eliminar experiencia?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 font-bold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No hay experiencia laboral registrada para este empleado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>