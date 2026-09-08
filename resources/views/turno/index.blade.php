<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Directorio de Turnos del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Turnos</h3>
                    <a href="{{ route('turno.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                        + Registrar Turno
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 font-bold text-gray-600 border-b w-16">ID</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Turno (Sistema)</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Jornada Asociada (Empleador)</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-center w-48">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($turnos as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ $item->id }}</td>
                                    <td class="p-3 border-b font-medium text-gray-800">{{ $item->nombre }}</td>
                                    <td class="p-3 border-b">
                                        {{ $item->emplJornadaTrabajo->jornada_trabajo ?? 'N/A' }} 
                                        <span class="text-xs text-gray-500">(Cód: {{ $item->emplJornadaTrabajo->codigo ?? '' }})</span>
                                    </td>
                                    <td class="p-3 border-b text-center flex justify-center gap-3 mt-1">
                                        <a href="{{ route('turno.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 text-sm font-bold">Editar</a>
                                        
                                        <form action="{{ route('turno.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este turno del sistema?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-500">
                                        No hay turnos configurados en el sistema.
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