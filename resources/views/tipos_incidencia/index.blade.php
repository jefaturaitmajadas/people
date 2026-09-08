<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Catálogo: Tipos de Incidencias
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between mb-4">
                {{-- Puedes cambiar la ruta de 'dashboard' por la ruta principal de tu sistema --}}
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline font-semibold">← Volver al Inicio</a>
                <a href="{{ route('tipo-incidencias.create') }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Nuevo Tipo de Incidencia
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 border-b">Nombre / Categoría</th>
                        <th class="p-3 border-b text-center">Impacto en Nómina</th>
                        <th class="p-3 border-b text-center">Uso Registrado</th>
                        <th class="p-3 border-b text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiposIncidencia as $tipo)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-bold text-gray-700">
                                {{ $tipo->nombre }}
                            </td>
                            <td class="p-3 border-b text-center">
                                @if($tipo->descuenta_salario)
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Descuenta Salario</span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Con Goce de Salario</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center text-sm text-gray-500">
                                {{ $tipo->incidencias_count ?? 0 }} registros
                            </td>
                            <td class="p-3 border-b text-center flex justify-center gap-3 items-center mt-1">
                                <a href="{{ route('tipo-incidencias.edit', $tipo->id) }}" class="text-amber-600 hover:text-amber-900 font-bold">Editar</a>
                                
                                <form action="{{ route('tipo-incidencias.destroy', $tipo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este tipo de incidencia?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No hay tipos de incidencias creados aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>