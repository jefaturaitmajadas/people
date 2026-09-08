<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Ingreso: {{ $ingreso->descripcion }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('ingresos.update', $ingreso->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="bg-green-50 border border-green-200 p-4 rounded mb-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-green-800">Modalidad: {{ $ingreso->es_recurrente ? 'Recurrente (Infinito)' : 'Pago Único / Cuotas' }}</h4>
                        <p class="text-sm text-green-600">Tipo: {{ $ingreso->tipoIngreso->nombre }}</p>
                    </div>
                    @if(!$ingreso->es_recurrente)
                        <a href="{{ route('ingreso-pagos.index', $ingreso->id) }}" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded shadow text-sm font-bold">
                            Ver Pagos Proyectados
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Descripción Corta *</label>
                        <input type="text" name="descripcion" value="{{ $ingreso->descripcion }}" class="w-full border rounded p-2" required>
                    </div>

                    @if(!$ingreso->es_recurrente)
                        <div>
                            <label class="block font-bold text-sm text-gray-500 mb-2">Monto Total Original</label>
                            <input type="text" value="Q. {{ number_format($ingreso->monto_total, 2) }}" class="w-full border rounded p-2 bg-gray-100 text-gray-500" readonly>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-500 mb-2">Pagos Proyectados</label>
                            <input type="text" value="{{ $ingreso->cantidad_cuotas }} pagos" class="w-full border rounded p-2 bg-gray-100 text-gray-500" readonly>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-sm text-gray-500 mb-2">Monto por Nómina</label>
                        <input type="text" value="Q. {{ number_format($ingreso->monto_cuota, 2) }}" class="w-full border rounded p-2 bg-gray-100 text-gray-500" readonly>
                    </div>

                    @if($ingreso->es_recurrente)
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Fin (Opcional)</label>
                            <input type="date" name="fecha_fin" value="{{ $ingreso->fecha_fin }}" class="w-full border rounded p-2">
                        </div>
                    @endif

                    <div class="col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="w-full border rounded p-2">{{ $ingreso->observaciones }}</textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="estado" class="form-checkbox h-5 w-5 text-green-600" {{ $ingreso->estado ? 'checked' : '' }}>
                        <span class="ml-2 font-bold text-gray-700">Ingreso Activo en Nómina</span>
                    </label>
                </div>

                <div class="flex justify-between border-t pt-4 mt-6">
                    <button type="button" onclick="if(confirm('¿Seguro que deseas eliminar este ingreso?')) document.getElementById('form-delete').submit();" class="text-red-600 hover:text-red-900 font-bold py-2 px-4">
                        Eliminar Registro
                    </button>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('ingresos.index', $ingreso->empleado_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                        <button type="submit" class="bg-green-600 hover:bg-green-800 text-white py-2 px-4 rounded shadow">Actualizar Ingreso</button>
                    </div>
                </div>
            </form>

            <form id="form-delete" action="{{ route('ingresos.destroy', $ingreso->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>
</x-app-layout>