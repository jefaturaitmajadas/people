<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Tipo de Incapacidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tipo-incapacidad.update', $tipo_incapacidad->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Tipo de Incapacidad *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $tipo_incapacidad->nombre) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Porcentaje (%) *</label>
                            <input type="number" step="0.01" min="0" max="100" name="porcentaje" value="{{ old('porcentaje', $tipo_incapacidad->porcentaje) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('porcentaje') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Porcentaje por Día (%) *</label>
                            <input type="number" step="0.01" min="0" max="100" name="porcentaje_por_dia" value="{{ old('porcentaje_por_dia', $tipo_incapacidad->porcentaje_por_dia) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('porcentaje_por_dia') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('tipo-incapacidad.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar Registro</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>