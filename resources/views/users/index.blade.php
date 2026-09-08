<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Gestión de Usuarios y Accesos
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Listado de Usuarios</h3>
                    <p class="text-sm text-gray-500">Administra quién tiene acceso al sistema y a qué empresas.</p>
                </div>
                <a href="{{ route('users.create') }}" class="mt-4 md:mt-0 bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow">
                    + Nuevo Usuario
                </a>
            </div>

            <!-- Alertas de éxito o error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm">
                            <th class="p-3 border-b font-bold">Usuario / Correo</th>
                            <th class="p-3 border-b font-bold text-center">Rol del Sistema</th>
                            <th class="p-3 border-b font-bold">Empresas Asignadas (Entornos)</th>
                            <th class="p-3 border-b font-bold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 border-b">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-800 block">{{ $user->name }}</span>
                                            <span class="text-sm text-gray-500">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="p-3 border-b text-center">
                                    @if($user->rol == 'administrador')
                                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full border border-purple-200">
                                            Administrador
                                        </span>
                                    @elseif($user->rol == 'gerente')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                                            Gerente
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">
                                            Generalista
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="p-3 border-b text-sm">
                                    @if($user->rol == 'administrador')
                                        <span class="text-purple-600 font-semibold italic">Acceso Total (Todas las empresas)</span>
                                    @else
                                        @if($user->empresas->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->empresas as $empresa)
                                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded border">
                                                        {{ $empresa->nombre }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-red-500 text-xs font-bold flex items-center">
                                                ⚠️ Ninguna empresa asignada
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                
                                <td class="p-3 border-b text-center">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">
                                            Editar
                                        </a>
                                        
                                        @if(Auth::id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás completamente seguro de eliminar este usuario? Perderá el acceso al ERP.');">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm italic" title="No puedes eliminarte a ti mismo">Tú</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-500 font-medium">
                                    No hay usuarios registrados en el sistema.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>