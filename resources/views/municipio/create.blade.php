<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Municipio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('municipio.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Municipio *</label>
                        <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" placeholder="Ej: Guatemala, Mixco, Villa Nueva" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Código Empleador *</label>
                            <input type="number" name="codigo_empl" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Ej: 101" required>
                            @error('codigo_empl') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Código IGSS *</label>
                            <input type="text" name="codigo_igss" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Ej: 0101" required>
                            @error('codigo_igss') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Departamento Asociado (IGSS) *</label>
                        <select name="id_igss_departamento" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            <option value="">Seleccione el departamento</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}">Depto {{ $depto->departamento }} - {{ $depto->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_igss_departamento') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('municipio.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Guardar Municipio</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>