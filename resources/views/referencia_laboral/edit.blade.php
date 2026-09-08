<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Referencia Laboral: {{ $referencia->empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('referencia-laboral.update', $referencia->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Nombre del Contacto *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $referencia->nombre) }}" class="w-full border rounded p-2" required>
                    @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Empresa *</label>
                    <input type="text" name="empresa" value="{{ old('empresa', $referencia->empresa) }}" class="w-full border rounded p-2" required>
                    @error('empresa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Teléfono *</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $referencia->telefono) }}" class="w-full border rounded p-2" required>
                    @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('referencia-laboral.index', $referencia->empleado_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Actualizar Referencia</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>