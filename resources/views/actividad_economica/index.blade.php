<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades Económicas (IGSS)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Clasificación</h3>
                    <a href="{{ route('actividad-economica.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                        + Nueva Actividad
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
                                <th class="p-3 font-bold text-gray-600 border-b">Cat.</th>
                                <th class="p-3 font-bold text-gray-600 border-b">División</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Grupo / Clase</th>
                                <th class="p-3 font-bold text-gray-600 border-b w-1/3">Descripción</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($actividades as $act)
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="p-3 border-b text-center font-bold">{{ $act->categoria }}</td>
                                    <td class="p-3 border-b">{{ $act->division }}</td>
                                    <td class="p-3 border-b">{{ $act->grupo }} <br> <span class="text-xs text-gray-500">{{ $act->clase }}</span></td>
                                    <td class="p-3 border-b">{{ $act->descripcion }}</td>
                                    <td class="p-3 border-b text-center flex justify-center gap-2 mt-2">
                                        <a href="{{ route('actividad-economica.edit', $act->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                        <form action="{{ route('actividad-economica.destroy', $act->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminarla?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500">
                                        No hay actividades económicas registradas.
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