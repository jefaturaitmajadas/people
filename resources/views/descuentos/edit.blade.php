<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Descuento: {{ $descuento->descripcion }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('descuentos.update', $descuento->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="bg-blue-50 border border-blue-200 p-4 rounded mb-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-blue-800">Modalidad: {{ $descuento->es_recurrente ? 'Recurrente (Infinito)' : 'Préstamo / Anticipo (Cuotas)' }}</h4>
                        <p class="text-sm text-blue-600">Tipo: {{ $descuento->tipoDescuento->nombre }}</p>
                    </div>
                    @if(!$descuento->es_recurrente)
                        <a href="{{ route('descuento-cuotas.index', $descuento->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded shadow text-sm font-bold">
                            Ver Tabla de Pagos
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Descripción Corta *</label>
                        <input type="text" name="descripcion" value="{{ $descuento->descripcion }}" class="w-full border rounded p-2" required>
                    </div>

                    @if(!$descuento->es_recurrente)
                        <div>
                            <label class="block font-bold text-sm text-gray-500 mb-2">Monto Total Original</label>
                            <input type="text" value="Q. {{ number_format($descuento->monto_total, 2) }}" class="w-full border rounded p-2 bg-gray-100 text-gray-500" readonly>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-500 mb-2">Cantidad de Cuotas</label>
                            <input type="text" value="{{ $descuento->cantidad_cuotas }} cuotas" class="w-full border rounded p-2 bg-gray-100 text-gray-500" readonly>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-gray-500 mb-2">Saldo Pendiente</label>
                            <input type="text" value="Q. {{ number_format($descuento->saldo, 2) }}" class="w-full border rounded p-2 bg-red-50 text-red-700 font-bold" readonly>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-sm text-gray-500 mb-2">Cuota por Nómina</label>
                        <input type="text" value="Q. {{ number_format($descuento->cuota, 2) }}" class="w-full border rounded p-2 bg-gray-100 text-gray-500" readonly>
                        <p class="text-xs text-gray-400 mt-1">Por seguridad contable, los montos no son editables tras su creación.</p>
                    </div>

                    @if($descuento->es_recurrente)
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Fin (Opcional)</label>
                            <input type="date" name="fecha_fin" value="{{ $descuento->fecha_fin }}" class="w-full border rounded p-2">
                        </div>
                    @endif

                    <div class="col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Observaciones</label>
                        <textarea name="observaciones" rows="2" class="w-full border rounded p-2">{{ $descuento->observaciones }}</textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="estado" class="form-checkbox h-5 w-5 text-blue-600" {{ $descuento->estado ? 'checked' : '' }}>
                        <span class="ml-2 font-bold text-gray-700">Descuento Activo en Nómina</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Apágalo si deseas pausar el cobro temporalmente o cancelarlo.</p>
                </div>

                <div class="flex justify-between border-t pt-4 mt-6">
                    <!-- Botón Eliminar -->
                    <button type="button" onclick="if(confirm('¿Seguro que deseas eliminar este descuento y toda su tabla de amortización?')) document.getElementById('form-delete').submit();" class="text-red-600 hover:text-red-900 font-bold py-2 px-4">
                        Eliminar Registro
                    </button>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('descuentos.index', $descuento->empleado_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded shadow">Actualizar Descuento</button>
                    </div>
                </div>
            </form>

            <form id="form-delete" action="{{ route('descuentos.destroy', $descuento->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>

        </div>
    </div>
</x-app-layout>