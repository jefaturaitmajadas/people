<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Puesto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('puesto.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Puesto *</label>
                            <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Ej: Gerente General" required>
                            @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Salario Base *</label>
                            <input type="number" step="0.01" min="0" name="salario_base" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Ej: 5000.00" required>
                            @error('salario_base') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 mt-2 border-t pt-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Descriptor de Puesto *</label>
                            <textarea name="descriptor_de_puesto" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Descripción general del puesto..." required></textarea>
                            @error('descriptor_de_puesto') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Responsabilidades *</label>
                            <textarea name="responsabilidades" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Principales responsabilidades..." required></textarea>
                            @error('responsabilidades') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Funciones Generales *</label>
                            <textarea name="funciones_generales" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Funciones de carácter general..." required></textarea>
                            @error('funciones_generales') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Funciones Específicas *</label>
                            <textarea name="funciones_especificas" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" placeholder="Tareas y funciones específicas al puesto..." required></textarea>
                            @error('funciones_especificas') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('puesto.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Guardar Puesto</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>