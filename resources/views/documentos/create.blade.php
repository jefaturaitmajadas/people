<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Registrar Documento para: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('documentos.store', $empleado->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Tipo de Documento *</label>
                    <select name="tipo_documento_id" class="w-full border rounded p-2" required>
                        <option value="">Seleccione un tipo...</option>
                        @foreach($tipos_documento as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_documento_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Descripción *</label>
                    <input type="text" name="descripcion" class="w-full border rounded p-2" placeholder="Ej: DPI original, Licencia Tipo C..." required>
                    @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Emisión *</label>
                        <input type="date" name="fecha_emision" class="w-full border rounded p-2" required>
                        @error('fecha_emision') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="w-full border rounded p-2">
                        <p class="text-xs text-gray-500 mt-1">Dejar en blanco si no vence.</p>
                        @error('fecha_vencimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="w-full border rounded p-2" placeholder="Detalles adicionales..."></textarea>
                    @error('observaciones') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- NUEVO CAMPO: ARCHIVO -->
                <div class="mb-6 border-t mt-4 pt-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Adjuntar Documento (Opcional)</label>
                    <input type="file" name="archivo" accept=".pdf, .jpg, .jpeg, .png" class="w-full border rounded p-2 text-sm bg-white">
                    <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG. Tamaño máximo: 5MB.</p>
                    @error('archivo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('documentos.index', $empleado->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Guardar Documento</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>