<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Catálogo: Tipos de Descuentos</h2>
    </x-slot>

    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Listado de Tipos</h3>
                <a href="{{ route('tipo-descuentos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Nuevo Tipo
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
                        <th class="p-3 border-b">ID</th>
                        <th class="p-3 border-b">Nombre del Tipo de Descuento</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tipos as $tipo)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b text-gray-500">{{ $tipo->id }}</td>
                            <td class="p-3 border-b font-bold">{{ $tipo->nombre }}</td>
                            <td class="p-3 border-b text-center flex justify-center gap-3">
                                <a href="{{ route('tipo-descuentos.edit', $tipo->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                <form action="{{ route('tipo-descuentos.destroy', $tipo->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este tipo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500">No hay tipos de descuentos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>