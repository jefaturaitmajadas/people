<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Tipo de Descuento: {{ $tipo_descuento->nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('tipo-descuentos.update', $tipo_descuento->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Nombre del Tipo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $tipo_descuento->nombre) }}" class="w-full border rounded p-2" required>
                    @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4 mt-6">
                    <a href="{{ route('tipo-descuentos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Actualizar Registro</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>