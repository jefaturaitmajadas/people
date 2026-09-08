<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Discapacidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('discapacidad.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre o Detalle de Discapacidad *</label>
                        <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" placeholder="Ej: Ceguera total, Sordera parcial" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Asignar a Tipo de Discapacidad (Catálogo Empleador) *</label>
                        <select name="id_empl_tipo_capacidad" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            <option value="">Seleccione el tipo correspondiente</option>
                            @foreach($tiposDiscapacidad as $tipo)
                                <option value="{{ $tipo->id }}">Código {{ $tipo->codigo }} - {{ $tipo->discapacidad }}</option>
                            @endforeach
                        </select>
                        @error('id_empl_tipo_capacidad') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('discapacidad.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Guardar Discapacidad</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>