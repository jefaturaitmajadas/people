<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Documento de: {{ $documento->empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('documentos.update', $documento->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Tipo de Documento *</label>
                    <select name="tipo_documento_id" class="w-full border rounded p-2" required>
                        <option value="">Seleccione un tipo...</option>
                        @foreach($tipos_documento as $tipo)
                            <option value="{{ $tipo->id }}" {{ $documento->tipo_documento_id == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_documento_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Descripción *</label>
                    <input type="text" name="descripcion" value="{{ old('descripcion', $documento->descripcion) }}" class="w-full border rounded p-2" required>
                    @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Emisión *</label>
                        <input type="date" name="fecha_emision" value="{{ old('fecha_emision', $documento->fecha_emision) }}" class="w-full border rounded p-2" required>
                        @error('fecha_emision') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $documento->fecha_vencimiento) }}" class="w-full border rounded p-2">
                        @error('fecha_vencimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="w-full border rounded p-2">{{ old('observaciones', $documento->observaciones) }}</textarea>
                    @error('observaciones') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- NUEVO CAMPO: ARCHIVO -->
                <div class="mb-6 border-t mt-4 pt-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Adjuntar/Reemplazar Documento</label>
                    
                    @if($documento->archivo_ruta)
                        <div class="mb-3 p-3 bg-blue-50 rounded border border-blue-200 flex justify-between items-center">
                            <span class="text-sm text-blue-800">Ya hay un archivo adjunto.</span>
                            <a href="{{ asset('storage/' . $documento->archivo_ruta) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">Ver Archivo Actual</a>
                        </div>
                    @endif

                    <input type="file" name="archivo" accept=".pdf, .jpg, .jpeg, .png" class="w-full border rounded p-2 text-sm bg-white">
                    <p class="text-xs text-gray-500 mt-1">Sube un nuevo archivo si deseas reemplazar el actual.</p>
                    @error('archivo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('documentos.index', $documento->empleado_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Actualizar Documento</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>