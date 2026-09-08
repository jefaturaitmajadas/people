<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Nivel Educativo (Empleador)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('empl-nivel-educativo.update', $nivel_educativo->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Código *</label>
                        <input type="number" name="codigo" value="{{ old('codigo', $nivel_educativo->codigo) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('codigo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nivel Educativo *</label>
                        <input type="text" name="nivel_educativo" value="{{ old('nivel_educativo', $nivel_educativo->nivel_educativo) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        @error('nivel_educativo') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('empl-nivel-educativo.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar Registro</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>