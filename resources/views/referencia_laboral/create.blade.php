<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Añadir Referencia Laboral para: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('referencia-laboral.store', $empleado->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Nombre del Contacto *</label>
                    <input type="text" name="nombre" class="w-full border rounded p-2" placeholder="Ej: Juan Pérez" required>
                    @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Empresa *</label>
                    <input type="text" name="empresa" class="w-full border rounded p-2" placeholder="Ej: Corporación ABC" required>
                    @error('empresa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Teléfono *</label>
                    <input type="text" name="telefono" class="w-full border rounded p-2" placeholder="Ej: 5555-1234" required>
                    @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('referencia-laboral.index', $empleado->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Guardar Referencia</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>