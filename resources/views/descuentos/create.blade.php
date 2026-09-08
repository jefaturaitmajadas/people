<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Registrar Descuento para: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('descuentos.store', $empleado->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Tipo de Descuento *</label>
                        <select name="tipo_descuento_id" class="w-full border rounded p-2" required>
                            <option value="">Seleccione...</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Descripción Corta *</label>
                        <input type="text" name="descripcion" class="w-full border rounded p-2" placeholder="Ej: Préstamo Cooperativa / Pensión hijo" required>
                    </div>
                </div>

                <!-- SELECCIÓN DEL MODELO DE COBRO -->
                <div class="mb-6 p-4 border rounded bg-blue-50 border-blue-200">
                    <label class="block font-bold text-sm text-blue-800 mb-3">Modalidad de Cobro</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_cobro" value="prestamo" class="form-radio text-blue-600" checked onchange="toggleCobro()">
                            <span class="ml-2 text-sm font-bold text-gray-700">Préstamo / Anticipo (Cuotas limitadas)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_cobro" value="recurrente" class="form-radio text-blue-600" onchange="toggleCobro()">
                            <span class="ml-2 text-sm font-bold text-gray-700">Recurrente (Sin límite, ej: Pensión)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" id="zona_calculo">
                    <div id="div_total">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Monto Total *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 rounded-l-md font-bold">Q.</span>
                            <input type="number" step="0.01" id="monto_total" name="monto_total" class="w-full border rounded-r p-2" onkeyup="calcularCuota()">
                        </div>
                    </div>

                    <div id="div_cuotas">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Cantidad de Cuotas *</label>
                        <input type="number" id="cantidad_cuotas" name="cantidad_cuotas" class="w-full border rounded p-2" onkeyup="calcularCuota()" onchange="calcularCuota()">
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Cuota por Nómina *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 rounded-l-md font-bold">Q.</span>
                            <!-- Se pone readonly por defecto porque inicia en modo préstamo -->
                            <input type="number" step="0.01" id="cuota" name="cuota" class="w-full border rounded-r p-2 bg-gray-100" readonly required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Inicio *</label>
                        <input type="date" name="fecha_inicio" class="w-full border rounded p-2" required>
                        <p class="text-xs text-gray-500 mt-1">Quincena o mes donde se realizará el 1er cobro.</p>
                    </div>

                    <div class="col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="w-full border rounded p-2"></textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="estado" class="form-checkbox h-5 w-5 text-blue-600" checked>
                        <span class="ml-2 font-bold text-gray-700">Descuento Activo</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('descuentos.index', $empleado->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-red-600 hover:bg-red-800 text-white py-2 px-4 rounded shadow">Guardar y Proyectar</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Script para calcular la cuota y manejar las opciones de cobro -->
    <script>
        function toggleCobro() {
            const tipo = document.querySelector('input[name="tipo_cobro"]:checked').value;
            const divTotal = document.getElementById('div_total');
            const divCuotas = document.getElementById('div_cuotas');
            const inputCuota = document.getElementById('cuota');

            if (tipo === 'prestamo') {
                // Modo Préstamo: Pide total y cuotas, calcula cuota.
                divTotal.style.display = 'block';
                divCuotas.style.display = 'block';
                inputCuota.readOnly = true;
                inputCuota.classList.add('bg-gray-100');
                calcularCuota();
            } else {
                // Modo Recurrente (Pensión): Oculta total y cuotas, permite escribir la cuota directo.
                divTotal.style.display = 'none';
                divCuotas.style.display = 'none';
                inputCuota.readOnly = false;
                inputCuota.classList.remove('bg-gray-100');
                
                // Limpiar valores
                document.getElementById('monto_total').value = '';
                document.getElementById('cantidad_cuotas').value = '';
                inputCuota.value = '';
            }
        }

        function calcularCuota() {
            const tipo = document.querySelector('input[name="tipo_cobro"]:checked').value;
            if(tipo !== 'prestamo') return; // Si es recurrente, no calcular nada.

            const total = parseFloat(document.getElementById('monto_total').value) || 0;
            const cuotas = parseInt(document.getElementById('cantidad_cuotas').value) || 0;
            const inputCuota = document.getElementById('cuota');

            if (total > 0 && cuotas > 0) {
                inputCuota.value = (total / cuotas).toFixed(2);
            } else {
                inputCuota.value = '';
            }
        }
        
        // Ejecutar toggleCobro al cargar la página por si se recarga conservando estado
        document.addEventListener('DOMContentLoaded', toggleCobro);
    </script>
</x-app-layout>