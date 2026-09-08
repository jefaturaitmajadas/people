<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial de Cargas (Planillas)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Planillas Cargadas</h3>
                    <a href="{{ route('cargas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                        + Cargar Nueva Planilla
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
                            <th class="p-3 font-bold text-gray-600 border-b">ID</th>
                            <th class="p-3 font-bold text-gray-600 border-b">Periodo</th>
                            <th class="p-3 font-bold text-gray-600 border-b">Fecha de Carga</th>
                            <th class="p-3 font-bold text-gray-600 border-b text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cargas as $carga)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $carga->id }}</td>
                                <td class="p-3 border-b">{{ $carga->mes_planilla }} {{ $carga->anio_planilla }}</td>
                                <td class="p-3 border-b">{{ \Carbon\Carbon::parse($carga->fecha_carga)->format('d/m/Y') }}</td>
                                <td class="p-3 border-b text-center flex justify-center gap-4">
                                    <a href="{{ route('cargas.show', $carga->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">Ver Detalle</a>
                                    
                                    <form action="{{ route('cargas.destroy', $carga->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta carga y todo su detalle?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 border-b text-center text-gray-500 py-6">
                                    No hay planillas cargadas aún. Haz clic en "Cargar Nueva Planilla" para empezar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>