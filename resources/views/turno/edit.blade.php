<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Turno') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('turno.update', $turno->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Turno (Sistema) *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $turno->nombre) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Asignar a Jornada de Trabajo (Catálogo Empleador) *</label>
                        <select name="id_empl_jornada" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @foreach($jornadas as $jornada)
                                <option value="{{ $jornada->id }}" {{ $turno->id_empl_jornada == $jornada->id ? 'selected' : '' }}>
                                    Código {{ $jornada->codigo }} - {{ $jornada->jornada_trabajo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_empl_jornada') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('turno.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar Turno</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>