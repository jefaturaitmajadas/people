<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catálogo de Ocupaciones (IGSS)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Ocupaciones</h3>
                    <a href="{{ route('igss-ocupacion.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                        + Registrar Ocupación
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold text-gray-600 border-b">Gran Grupo</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Sub-G. Principal</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Sub-Grupo</th>
                                <th class="p-3 font-bold text-gray-600 border-b">G. Primario</th>
                                <th class="p-3 font-bold text-gray-600 border-b w-1/3">Descripción</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ocupaciones as $item)
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="p-3 border-b">{{ $item->gran_grupo }}</td>
                                    <td class="p-3 border-b">{{ $item->sub_grupo_principal }}</td>
                                    <td class="p-3 border-b">{{ $item->sub_grupo }}</td>
                                    <td class="p-3 border-b">{{ $item->grupo_primario }}</td>
                                    <td class="p-3 border-b font-medium text-gray-800">{{ $item->descripcion }}</td>
                                    <td class="p-3 border-b text-center flex justify-center gap-2 mt-2">
                                        <a href="{{ route('igss-ocupacion.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                        
                                        <form action="{{ route('igss-ocupacion.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta ocupación?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500">
                                        No hay ocupaciones registradas aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>