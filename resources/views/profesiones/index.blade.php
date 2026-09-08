<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Catálogo de Profesiones y Oficios
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-700">Profesiones Registradas</h3>
                <a href="{{ route('profesiones.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    + Nueva Profesión
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-3 border-b font-bold w-16 text-center">ID</th>
                        <th class="p-3 border-b font-bold">Nombre de la Profesión / Oficio</th>
                        <th class="p-3 border-b font-bold text-center w-48">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profesiones as $profesion)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-3 border-b text-center text-gray-500">{{ $profesion->id }}</td>
                            <td class="p-3 border-b font-bold text-gray-800 uppercase">{{ $profesion->nombre }}</td>
                            <td class="p-3 border-b text-center space-x-2">
                                <a href="{{ route('profesiones.edit', $profesion->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">Editar</a>
                                
                                <form action="{{ route('profesiones.destroy', $profesion->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta profesión?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm ml-2">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500">No hay profesiones registradas aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>