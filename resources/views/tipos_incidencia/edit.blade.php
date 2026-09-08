<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Editar Tipo de Incidencia
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="mb-6">
                <a href="{{ route('tipo-incidencias.index') }}" class="text-blue-600 hover:underline font-semibold">← Volver al Catálogo</a>
            </div>

            <form action="{{ route('tipo-incidencias.update', $tipoIncidencia->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <label for="nombre" class="block font-medium text-sm text-gray-700 mb-1">Nombre / Categoría <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $tipoIncidencia->nombre) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                    @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Descuenta Salario --}}
                <div>
                    <label for="descuenta_salario" class="block font-medium text-sm text-gray-700 mb-1">Impacto en Nómina <span class="text-red-500">*</span></label>
                    <select name="descuenta_salario" id="descuenta_salario" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        <option value="1" {{ old('descuenta_salario', $tipoIncidencia->descuenta_salario) == 1 ? 'selected' : '' }}>
                            Descuenta Salario (Faltas / Suspensiones sin goce)
                        </option>
                        <option value="0" {{ old('descuenta_salario', $tipoIncidencia->descuenta_salario) == 0 ? 'selected' : '' }}>
                            Con Goce de Salario (Permisos pagados)
                        </option>
                    </select>
                    @error('descuenta_salario') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Porcentaje Descuento --}}
                <div id="div_porcentaje">
                    <label for="porcentaje_descuento" class="block font-medium text-sm text-gray-700 mb-1">
                        Porcentaje a Descontar del Salario Diario <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        {{-- CORRECCIÓN AQUÍ: se obtiene directamente el valor guardado en BD --}}
                        <input type="number" step="0.01" min="0" max="100" name="porcentaje_descuento" id="porcentaje_descuento" 
                            value="{{ old('porcentaje_descuento', $tipoIncidencia->porcentaje_descuento) }}" 
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full pl-3 pr-8" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Ejemplo: <strong>100%</strong> (Ausencia total), <strong>66.67%</strong> (El IGSS paga 2/3 y la empresa subsidia el 1/3 restante).</p>
                    @error('porcentaje_descuento') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-amber-600 hover:bg-amber-800 text-white font-bold py-2 px-6 rounded shadow">
                        Actualizar Categoría
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

            function togglePorcentaje(isUserChange = false) {
                if (selectDescuenta.value == '0') {
                    inputPorcentaje.value = '0.00';
                    inputPorcentaje.readOnly = true;
                    divPorcentaje.classList.add('opacity-50');
                } else {
                    inputPorcentaje.readOnly = false;
                    divPorcentaje.classList.remove('opacity-50');
                    
                    // Solo asigna 100 si fue una acción explícita del usuario al cambiar el select y estaba en 0
                    if (isUserChange && (inputPorcentaje.value == '0.00' || inputPorcentaje.value == '0')) {
                        inputPorcentaje.value = '100.00';
                    }
                }
            }

            selectDescuenta.addEventListener('change', () => togglePorcentaje(true));
            
            // Carga inicial sin alterar el valor proveniente de la base de datos
            togglePorcentaje(false);
        });
    </script>
</x-app-layout>