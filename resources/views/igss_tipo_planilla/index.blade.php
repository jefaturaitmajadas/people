<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tipos de Planilla (IGSS)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Planillas</h3>
                    <a href="{{ route('tipos-planilla.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                        + Registrar Tipo de Planilla
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-100 text-sm">
                                <th class="p-3 font-bold text-gray-600 border-b w-16">ID</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Empresa</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Identificación</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Nombre</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Afil/Per</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Cadena Generada</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-center w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($planillas as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ $item->id }}</td>
                                    <td class="p-3 border-b">{{ $item->empresa->nombre ?? 'N/A' }}</td>
                                    <td class="p-3 border-b">{{ $item->identificacion }}</td>
                                    <td class="p-3 border-b">{{ $item->nombre }}</td>
                                    <td class="p-3 border-b">{{ $item->tipo_afiliado }} / {{ $item->periodo }}</td>
                                    <td class="p-3 border-b font-mono text-xs text-gray-600">{{ $item->cadena_concatenada }}</td>
                                    <td class="p-3 border-b text-center flex justify-center gap-3 mt-1">
                                        <a href="{{ route('tipos-planilla.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                        
                                        <form action="{{ route('tipos-planilla.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-gray-500">
                                        No hay tipos de planilla registrados.
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