<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Empresas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Empresas</h3>
                    <a href="{{ route('empresas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                        + Nueva Empresa
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
                            <th class="p-3 font-bold text-gray-600 border-b">Nombre</th>
                            <th class="p-3 font-bold text-gray-600 border-b">NIT</th>
                            <th class="p-3 font-bold text-gray-600 border-b text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empresas as $empresa)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $empresa->nombre }}</td>
                                <td class="p-3 border-b">{{ $empresa->nit ?? 'N/A' }}</td>
                                <td class="p-3 border-b text-center flex justify-center gap-2">
                                    <!-- NUEVO BOTÓN PARA VER EMPLEADOS -->
                                    <a href="{{ route('empresas.reporte_mensual', $empresa->id) }}" class="text-purple-600 hover:text-purple-900 text-sm font-bold">Reporte Mensual</a>
                                    <a href="{{ route('empresas.empleados', $empresa->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-bold">Ver Empleados</a>
                                    <a href="{{ route('empresas.edit', $empresa->id) }}" class="text-amber-600 hover:text-amber-900 text-sm">Editar</a>
                                    
                                    <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta empresa de forma lógica?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>