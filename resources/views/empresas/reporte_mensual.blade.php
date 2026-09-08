<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            <span>{{ __('Reporte Mensual: ') }} <span class="text-purple-600">{{ $empresa->nombre }}</span></span>
            <a href="{{ route('empresas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-sm py-1 px-3 rounded shadow">
                Volver a Empresas
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- BUSCADOR / FILTRO -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form action="{{ route('empresas.reporte_mensual', $empresa->id) }}" method="GET" class="flex items-end gap-4">
                    
                    <div class="w-1/3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Mes de Planilla</label>
                        <select name="mes" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            <option value="">Seleccione un mes</option>
                            @php
                                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            @endphp
                            @foreach($meses as $m)
                                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-1/3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Año</label>
                        <input type="number" name="anio" value="{{ $anio ?? date('Y') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    </div>

                    <div class="w-1/3">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded w-full shadow">
                            Generar Reporte
                        </button>
                    </div>
                </form>
            </div>

            <!-- RESULTADOS Y TABLA -->
            @if($mes && $anio)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-0">
                    <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Resultados para {{ $mes }} {{ $anio }}</h3>
                        <span class="text-sm text-gray-600">Registros encontrados: <strong>{{ $detalles->count() }}</strong></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-gray-800 text-white text-sm">
                                    <th class="p-3 font-bold border-b">Empleado</th>
                                    <th class="p-3 font-bold border-b text-right">Sueldo Base</th>
                                    <th class="p-3 font-bold border-b text-right">H. Extras</th>
                                    <th class="p-3 font-bold border-b text-right">Bonif. Ley</th>
                                    <th class="p-3 font-bold border-b text-right text-red-300">IGSS</th>
                                    <th class="p-3 font-bold border-b text-right text-red-300">ISR</th>
                                    <th class="p-3 font-bold border-b text-right text-red-300">Desc. Varios</th>
                                    <th class="p-3 font-bold border-b text-right text-green-300">Líquido Pagado</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($detalles as $detalle)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border-b font-medium">{{ $detalle->empleado->nombres }} {{ $detalle->empleado->apellidos }}</td>
                                        <td class="p-3 border-b text-right">Q {{ number_format($detalle->sueldo_base, 2) }}</td>
                                        <td class="p-3 border-b text-right">Q {{ number_format($detalle->horas_extras, 2) }}</td>
                                        <td class="p-3 border-b text-right">Q {{ number_format($detalle->bonificacion_ley, 2) }}</td>
                                        <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->desc_igss, 2) }}</td>
                                        <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->isr, 2) }}</td>
                                        <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->descuentos_varios, 2) }}</td>
                                        <td class="p-3 border-b text-right font-bold text-gray-800">Q {{ number_format($detalle->liquido, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-6 text-center text-gray-500">
                                            No se encontraron registros de planillas para esta empresa en {{ $mes }} {{ $anio }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                            @if($detalles->isNotEmpty())
                            <tfoot class="bg-purple-100 font-bold text-sm">
                                <tr>
                                    <td class="p-3 text-right uppercase text-purple-900">Total Empresa:</td>
                                    <td class="p-3 text-right text-purple-900">Q {{ number_format($detalles->sum('sueldo_base'), 2) }}</td>
                                    <td class="p-3 text-right text-purple-900">Q {{ number_format($detalles->sum('horas_extras'), 2) }}</td>
                                    <td class="p-3 text-right text-purple-900">Q {{ number_format($detalles->sum('bonificacion_ley'), 2) }}</td>
                                    <td class="p-3 text-right text-red-700">Q {{ number_format($detalles->sum('desc_igss'), 2) }}</td>
                                    <td class="p-3 text-right text-red-700">Q {{ number_format($detalles->sum('isr'), 2) }}</td>
                                    <td class="p-3 text-right text-red-700">Q {{ number_format($detalles->sum('descuentos_varios'), 2) }}</td>
                                    <td class="p-3 text-right text-green-700 text-base">Q {{ number_format($detalles->sum('liquido'), 2) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            @else
                <!-- Estado vacío antes de buscar -->
                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
                    <p class="text-gray-500">Selecciona un mes y un año en la parte superior y haz clic en "Generar Reporte" para ver el acumulado de la empresa.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>