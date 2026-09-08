<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Registrar Incidencia: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="mb-6">
                <a href="{{ route('incidencias.index', $empleado->id) }}" class="text-blue-600 hover:underline font-semibold">← Volver a Incidencias</a>
            </div>

            <form action="{{ route('incidencias.store', $empleado->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="tipo_incidencia_id" class="block font-medium text-sm text-gray-700 mb-1">Tipo de Incidencia <span class="text-red-500">*</span></label>
                    <select name="tipo_incidencia_id" id="tipo_incidencia_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        <option value="">Seleccione el tipo...</option>
                        @foreach($tiposIncidencia as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_incidencia_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }} {{ $tipo->descuenta_salario ? '(Descuenta Salario)' : '(Con Goce)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_incidencia_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="block font-medium text-sm text-gray-700 mb-1">Fecha Inicio <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        @error('fecha_inicio') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="fecha_fin" class="block font-medium text-sm text-gray-700 mb-1">Fecha Fin <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        @error('fecha_fin') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded border flex justify-between items-center">
                    <span class="text-sm text-gray-600 font-semibold">Total de días a aplicar:</span>
                    <span id="preview_dias" class="font-bold text-blue-700">0 días</span>
                </div>

                <div>
                    <label for="observaciones" class="block font-medium text-sm text-gray-700 mb-1">Observaciones / Motivo</label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" placeholder="Detalles, no. de boleta IGSS...">{{ old('observaciones') }}</textarea>
                    @error('observaciones') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="documento_adjunto" class="block font-medium text-sm text-gray-700 mb-1">Documento Soporte (Opcional)</label>
                    <input type="file" name="documento_adjunto" id="documento_adjunto" accept=".pdf,.png,.jpg,.jpeg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    <p class="text-xs text-gray-400 mt-1">Formatos: PDF, JPG, PNG (Max 4MB)</p>
                    @error('documento_adjunto') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded shadow">
                        Guardar Incidencia
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputInicio = document.getElementById('fecha_inicio');
            const inputFin = document.getElementById('fecha_fin');
            const displayDias = document.getElementById('preview_dias');

            function calcularDias() {
                if (inputInicio.value && inputFin.value) {
                    const inicio = new Date(inputInicio.value);
                    const fin = new Date(inputFin.value);
                    if (fin >= inicio) {
                        const diffTime = Math.abs(fin - inicio);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        displayDias.textContent = diffDays + (diffDays === 1 ? ' día' : ' días');
                    } else {
                        displayDias.textContent = 'Inválido';
                    }
                }
            }
            inputInicio.addEventListener('change', calcularDias);
            inputFin.addEventListener('change', calcularDias);
        });
    </script>
</x-app-layout>