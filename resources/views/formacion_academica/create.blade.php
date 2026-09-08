<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Registrar Formación para: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('formacion-academica.store', $empleado->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Grado Académico *</label>
                    <select name="grado_academico_id" class="w-full border rounded p-2" required>
                        <option value="">Seleccione un grado...</option>
                        @foreach($grados as $grado)
                            <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                        @endforeach
                    </select>
                    @error('grado_academico_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Institución *</label>
                    <input type="text" name="institucion" class="w-full border rounded p-2" placeholder="Ej: Universidad de San Carlos" required>
                    @error('institucion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="w-full border rounded p-2">
                        @error('fecha_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="w-full border rounded p-2">
                        <p class="text-xs text-gray-500 mt-1">Dejar en blanco si actualmente cursa este grado.</p>
                        @error('fecha_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('formacion-academica.index', $empleado->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Guardar Formación</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>