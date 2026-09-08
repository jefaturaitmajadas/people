<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Referencias Personales de: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <a href="{{ route('empleados.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver a Empleados</a>
                <a href="{{ route('referencia-personal.create', $empleado->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Añadir Referencia
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
                        <th class="p-3 border-b">Nombre de Contacto</th>
                        <th class="p-3 border-b">Empresa / Ocupación</th>
                        <th class="p-3 border-b">Teléfono</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referencias as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-medium">{{ $item->nombre }}</td>
                            <td class="p-3 border-b">{{ $item->empresa ?? 'N/A' }}</td>
                            <td class="p-3 border-b">{{ $item->telefono }}</td>
                            <td class="p-3 border-b text-center flex justify-center gap-3">
                                <a href="{{ route('referencia-personal.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                <form action="{{ route('referencia-personal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta referencia?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No hay referencias personales registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>