<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Reportar Horas Extras: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('horas-extras.store', $empleado->id) }}" method="POST">
                @csrf
                
                <div class="bg-indigo-50 border border-indigo-200 p-4 rounded mb-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-indigo-800">Tarifa Sugerida por Hora</h4>
                        <p class="text-sm text-indigo-600">Basado en el salario actual del empleado</p>
                    </div>
                    <div class="text-xl font-bold text-indigo-900">
                        Q. <span id="lbl_tarifa">{{ number_format($salario_hora, 2) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Mes/Fecha Presentación *</label>
                        <input type="date" name="fecha_presentacion" class="w-full border rounded p-2" required>
                    </div>
                    
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Cantidad de Horas *</label>
                        <input type="number" step="0.5" id="cantidad_horas" name="cantidad_horas" class="w-full border rounded p-2" onkeyup="calcularTotal()" onchange="calcularTotal()" required>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Tipo de Hora *</label>
                        <select id="factor_multiplicador" name="factor_multiplicador" class="w-full border rounded p-2" onchange="calcularTotal()" required>
                            <option value="1.5">Ordinarias (x1.5)</option>
                            <option value="2.0">Asuetos / Séptimo (x2.0)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Salario Base por Hora *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 rounded-l-md font-bold">Q.</span>
                            <!-- Se envía el salario con el que se congeló este cálculo -->
                            <input type="number" step="0.01" id="salario_por_hora_sugerido" name="salario_por_hora_sugerido" value="{{ $salario_hora }}" class="w-full border rounded-r p-2" onkeyup="calcularTotal()" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Total a Pagar Proyectado</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-white bg-indigo-600 border border-r-0 rounded-l-md font-bold">Q.</span>
                            <input type="text" id="total_pagar_visual" class="w-full border border-indigo-600 rounded-r p-2 font-bold text-indigo-700 bg-indigo-50" readonly>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="2" class="w-full border rounded p-2" placeholder="Ej: Trabajo en inventario de fin de mes"></textarea>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('horas-extras.index', $empleado->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white py-2 px-4 rounded shadow">Registrar Horas</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcularTotal() {
            const horas = parseFloat(document.getElementById('cantidad_horas').value) || 0;
            const tarifa = parseFloat(document.getElementById('salario_por_hora_sugerido').value) || 0;
            const factor = parseFloat(document.getElementById('factor_multiplicador').value) || 1.5;

            const total = (horas * tarifa * factor).toFixed(2);
            document.getElementById('total_pagar_visual').value = total;
        }
    </script>
</x-app-layout>