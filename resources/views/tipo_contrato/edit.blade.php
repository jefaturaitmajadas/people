<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Tipo de Contrato') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tipo-contrato.update', $tipo_contrato->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Tipo (Sistema) *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $tipo_contrato->nombre) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Asignar a Temporalidad (Catálogo Empleador) *</label>
                        <select name="id_empl_temp_con" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @foreach($temporalidades as $temp)
                                <option value="{{ $temp->id }}" {{ $tipo_contrato->id_empl_temp_con == $temp->id ? 'selected' : '' }}>
                                    Código {{ $temp->codigo }} - {{ $temp->temporalidad_contrato }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_empl_temp_con') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('tipo-contrato.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar Tipo de Contrato</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>