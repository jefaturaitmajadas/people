<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Directorio de Empleados</h2>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Personal Registrado</h3>
                <a href="{{ route('empleados.create') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    + Nuevo Empleado
                </a>
            </div>

            <!-- Barra de Búsqueda -->
            <div class="mb-4 flex items-center">
                <div class="relative w-full sm:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="buscador" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Buscar por nombre, puesto o depto...">
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm">
                            <th class="p-3 border-b font-bold">Foto</th>    
                            <th class="p-3 border-b font-bold">Nombre Completo</th>
                            <th class="p-3 border-b font-bold">Unidad / Depto</th>
                            <th class="p-3 border-b font-bold">Puesto</th>
                            <th class="p-3 border-b font-bold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <!-- Agregamos el ID "tablaEmpleados" al tbody -->
                    <tbody id="tablaEmpleados">
                        @foreach($empleados as $e)
                        <tr class="hover:bg-gray-50 transition-colors">
                            
                            <td class="p-3 border-b">
                                @if($e->foto_perfil)
                                    <img src="{{ asset('storage/' . $e->foto_perfil) }}" alt="Foto" class="h-10 w-10 rounded-full object-cover shadow border border-gray-200">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-black shadow border border-indigo-200">
                                        {{ substr($e->primer_nombre, 0, 1) }}{{ substr($e->primer_apellido, 0, 1) }}
                                    </div>
                                @endif
                            </td>

                            <td class="p-3 border-b font-medium text-gray-800">
                                {{ $e->primer_nombre }} {{ $e->segundo_nombre }} {{ $e->primer_apellido }} {{ $e->segundo_apellido }}
                            </td>
                            <td class="p-3 border-b text-gray-600">{{ $e->departamento->nombre ?? 'N/A' }}</td>
                            <td class="p-3 border-b text-gray-600">{{ $e->puesto->nombre ?? 'N/A' }}</td>
                            
                            <td class="p-3 border-b text-center">
                                <div class="flex justify-center items-center space-x-4">
                                    <a href="{{ route('empleados.edit', $e->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm bg-blue-50 px-3 py-1 rounded border border-blue-200 transition-colors">
                                        Abrir Expediente
                                    </a>
                                    
                                    <form action="{{ route('empleados.destroy', $e->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este empleado? Esta acción es irreversible.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-800 text-sm font-bold">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script de Búsqueda Instantánea -->
    <script>
        document.getElementById('buscador').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll('#tablaEmpleados tr');

            filas.forEach(function(fila) {
                let textoFila = fila.textContent.toLowerCase();
                if (textoFila.includes(filtro)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>