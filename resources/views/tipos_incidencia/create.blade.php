<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Registrar Tipo de Incidencia
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="mb-6">
                <a href="{{ route('tipo-incidencias.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver al Catálogo</a>
            </div>

            <form action="{{ route('tipo-incidencias.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="nombre" class="block font-medium text-sm text-gray-700 mb-1">Nombre / Categoría <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Ej. Suspensión IGSS, Permiso por Luto..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                    @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="descuenta_salario" class="block font-medium text-sm text-gray-700 mb-1">Impacto en Nómina <span class="text-red-500">*</span></label>
                    <select name="descuenta_salario" id="descuenta_salario" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        <option value="" disabled {{ old('descuenta_salario') === null ? 'selected' : '' }}>Seleccione el comportamiento...</option>
                        <option value="1" {{ old('descuenta_salario') === '1' ? 'selected' : '' }}>Descuenta Salario (Faltas / Suspensiones sin goce)</option>
                        <option value="0" {{ old('descuenta_salario') === '0' ? 'selected' : '' }}>Con Goce de Salario (Permisos pagados)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Si selecciona "Descuenta Salario", el motor restará los días al calcular la nómina.</p>
                    @error('descuenta_salario') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4" id="div_porcentaje">
                    <label for="porcentaje_descuento" class="block font-medium text-sm text-gray-700 mb-1">
                        Porcentaje a Descontar del Salario Diario <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="100" name="porcentaje_descuento" id="porcentaje_descuento" 
                            {{-- En el edit usa: value="{{ old('porcentaje_descuento', $tipoIncidencia->porcentaje_descuento) }}" --}}
                            value="{{ old('porcentaje_descuento', '100.00') }}" 
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pl-3 pr-8">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Ejemplo: <strong>100%</strong> (Ausencia total), <strong>66.67%</strong> (El IGSS paga 2/3 y la empresa subsidia el 1/3 restante).
                    </p>
                    @error('porcentaje_descuento') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded shadow">
                        Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectDescuenta = document.getElementById('descuenta_salario');
        const inputPorcentaje = document.getElementById('porcentaje_descuento');
        const divPorcentaje = document.getElementById('div_porcentaje');

        function togglePorcentaje() {
            if(selectDescuenta.value == '0') {
                inputPorcentaje.value = '0.00';
                inputPorcentaje.readOnly = true;
                divPorcentaje.classList.add('opacity-50');
            } else {
                if(inputPorcentaje.value == '0.00') inputPorcentaje.value = '100.00';
                inputPorcentaje.readOnly = false;
                divPorcentaje.classList.remove('opacity-50');
            }
        }

        selectDescuenta.addEventListener('change', togglePorcentaje);
        togglePorcentaje(); // Ejecutar al cargar
    });
</script>
</x-app-layout>