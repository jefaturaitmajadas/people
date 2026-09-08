<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            <span>{{ __('Empleados de la Empresa: ') }} <span class="text-blue-600">{{ $empresa->nombre }}</span></span>
            <a href="{{ route('empresas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-sm py-1 px-3 rounded shadow">
                Volver a Empresas
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4">
                    <p class="text-sm text-gray-600">Total de empleados registrados: <strong>{{ $empresa->empleados->count() }}</strong></p>
                    <a href="{{ route('empleados.create') }}" class="text-sm bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded shadow">
                        + Registrar Nuevo Empleado
                    </a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 font-bold text-gray-600 border-b">Código</th>
                            <th class="p-3 font-bold text-gray-600 border-b">Nombre Completo</th>
                            <th class="p-3 font-bold text-gray-600 border-b">Correo Electrónico</th>
                            <th class="p-3 font-bold text-gray-600 border-b text-center">Acciones Rápidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empresa->empleados as $empleado)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $empleado->codigo_empleado }}</td>
                                <td class="p-3 border-b">{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
                                <td class="p-3 border-b">{{ $empleado->correo }}</td>
                                <td class="p-3 border-b text-center flex justify-center gap-3">
                                    <a href="{{ route('empleados.reporte', $empleado->id) }}" class="text-green-600 hover:text-green-900 text-sm font-bold">Historial</a>
                                    <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-amber-600 hover:text-amber-900 text-sm font-bold">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-500">
                                    Esta empresa aún no tiene empleados vinculados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>