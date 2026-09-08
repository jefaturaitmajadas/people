<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Cuentas Bancarias de {{ session('empresa_activa_nombre') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Cuentas Débito (Para Nómina)</h3>
                    <p class="text-sm text-gray-500">Estas cuentas se usarán para generar los archivos de los bancos.</p>
                </div>
                <a href="{{ route('empresa-cuentas.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Nueva Cuenta
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-3 border-b font-bold">Banco</th>
                        <th class="p-3 border-b font-bold">Número de Cuenta</th>
                        <th class="p-3 border-b font-bold">Descripción</th>
                        <th class="p-3 border-b font-bold">Estado</th>
                        <th class="p-3 border-b font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuentas as $cuenta)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b font-bold text-gray-800">{{ $cuenta->banco->nombre ?? 'N/A' }}</td>
                            <td class="p-3 border-b text-indigo-700 font-bold tracking-wide">{{ $cuenta->numero_cuenta }}</td>
                            <td class="p-3 border-b text-gray-600 text-sm">{{ $cuenta->descripcion }}</td>
                            <td class="p-3 border-b">
                                @if($cuenta->estado)
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">Activa</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded">Inactiva</span>
                                @endif
                            </td>
                            <td class="p-3 border-b text-center space-x-2">
                                <a href="{{ route('empresa-cuentas.edit', $cuenta->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">Editar</a>
                                
                                <form action="{{ route('empresa-cuentas.destroy', $cuenta->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta cuenta?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm ml-2">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No hay cuentas bancarias configuradas para esta empresa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>