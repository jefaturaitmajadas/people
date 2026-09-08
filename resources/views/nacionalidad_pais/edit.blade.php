<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Nacionalidad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('nacionalidad-pais.update', $nacionalidad->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Código *</label>
                            <input type="text" name="codigo" value="{{ old('codigo', $nacionalidad->codigo) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('codigo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">País *</label>
                            <input type="text" name="pais" value="{{ old('pais', $nacionalidad->pais) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('pais') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nacionalidad *</label>
                            <input type="text" name="nacionalidad" value="{{ old('nacionalidad', $nacionalidad->nacionalidad) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('nacionalidad') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('nacionalidad-pais.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>