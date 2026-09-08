<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Formación Académica: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver a Empleados</a>
                <a href="{{ route('formacion-academica.create', $empleado->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Añadir Formación
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
                        <th class="p-3 border-b">Grado Académico</th>
                        <th class="p-3 border-b">Institución</th>
                        <th class="p-3 border-b">Periodo</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($formaciones as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-medium">{{ $item->gradoAcademico->nombre ?? 'N/A' }}</td>
                            <td class="p-3 border-b">{{ $item->institucion }}</td>
                            <td class="p-3 border-b text-sm text-gray-600">
                                {{ $item->fecha_inicio ? \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y') : 'N/D' }} al 
                                {{ $item->fecha_fin ? \Carbon\Carbon::parse($item->fecha_fin)->format('d/m/Y') : 'Actualidad/En curso' }}
                            </td>
                            <td class="p-3 border-b text-center flex justify-center gap-3">
                                <a href="{{ route('formacion-academica.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                <form action="{{ route('formacion-academica.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta formación?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No hay formación académica registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>