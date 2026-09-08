<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Registrar Ingreso para: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('ingresos.store', $empleado->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Tipo de Ingreso *</label>
                        <select name="tipo_ingreso_id" class="w-full border rounded p-2" required>
                            <option value="">Seleccione...</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Descripción Corta *</label>
                        <input type="text" name="descripcion" class="w-full border rounded p-2" placeholder="Ej: Bono Decreto 37-2001 / Comisión Julio" required>
                    </div>
                </div>

                <div class="mb-6 p-4 border rounded bg-green-50 border-green-200">
                    <label class="block font-bold text-sm text-green-800 mb-3">Modalidad del Ingreso</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_cobro" value="recurrente" class="form-radio text-green-600" checked onchange="toggleModalidad()">
                            <span class="ml-2 text-sm font-bold text-gray-700">Recurrente (Ej: Bono de Ley fijo cada mes)</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_cobro" value="unico" class="form-radio text-green-600" onchange="toggleModalidad()">
                            <span class="ml-2 text-sm font-bold text-gray-700">Pago Único o Fraccionado (Ej: Comisión, Bono Anual)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div id="div_total" style="display: none;">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Monto Total *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 rounded-l-md font-bold">Q.</span>
                            <input type="number" step="0.01" id="monto_total" name="monto_total" class="w-full border rounded-r p-2" onkeyup="calcularCuota()">
                        </div>
                    </div>

                    <div id="div_cuotas" style="display: none;">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Dividido en (Pagos) *</label>
                        <input type="number" id="cantidad_cuotas" name="cantidad_cuotas" class="w-full border rounded p-2" placeholder="Ej: 1" onkeyup="calcularCuota()" onchange="calcularCuota()">
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Monto por Nómina *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 rounded-l-md font-bold">Q.</span>
                            <input type="number" step="0.01" id="monto_cuota" name="monto_cuota" class="w-full border rounded-r p-2" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Inicio *</label>
                        <input type="date" name="fecha_inicio" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" rows="1" class="w-full border rounded p-2"></textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="estado" class="form-checkbox h-5 w-5 text-green-600" checked>
                        <span class="ml-2 font-bold text-gray-700">Activo (Se pagará en la próxima planilla)</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('ingresos.index', $empleado->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-800 text-white py-2 px-4 rounded shadow">Guardar Ingreso</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModalidad() {
            const tipo = document.querySelector('input[name="tipo_cobro"]:checked').value;
            const divTotal = document.getElementById('div_total');
            const divCuotas = document.getElementById('div_cuotas');
            const inputCuota = document.getElementById('monto_cuota');

            if (tipo === 'unico') {
                divTotal.style.display = 'block';
                divCuotas.style.display = 'block';
                inputCuota.readOnly = true;
                inputCuota.classList.add('bg-gray-100');
                calcularCuota();
            } else {
                divTotal.style.display = 'none';
                divCuotas.style.display = 'none';
                inputCuota.readOnly = false;
                inputCuota.classList.remove('bg-gray-100');
                document.getElementById('monto_total').value = '';
                document.getElementById('cantidad_cuotas').value = '';
                inputCuota.value = '';
            }
        }

        function calcularCuota() {
            const tipo = document.querySelector('input[name="tipo_cobro"]:checked').value;
            if(tipo !== 'unico') return;

            const total = parseFloat(document.getElementById('monto_total').value) || 0;
            const cuotas = parseInt(document.getElementById('cantidad_cuotas').value) || 0;
            const inputCuota = document.getElementById('monto_cuota');

            if (total > 0 && cuotas > 0) {
                inputCuota.value = (total / cuotas).toFixed(2);
            } else {
                inputCuota.value = '';
            }
        }
        
        document.addEventListener('DOMContentLoaded', toggleModalidad);
    </script>
</x-app-layout>