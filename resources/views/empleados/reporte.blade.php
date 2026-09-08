<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            <span>{{ __('Historial de Planillas del Empleado') }}</span>
            <a href="{{ route('empleados.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-sm py-1 px-3 rounded">
                Volver a Empleados
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900">{{ $empleado->nombres }} {{ $empleado->apellidos }}</h3>
                <div class="grid grid-cols-3 gap-4 mt-2 text-sm text-gray-600">
                    <p><strong>Código:</strong> {{ $empleado->codigo_empleado }}</p>
                    <p><strong>Empresa:</strong> {{ $empleado->empresa->nombre ?? 'N/A' }}</p>
                    <p><strong>Total de Registros:</strong> {{ $detalles->count() }} planillas</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-800 text-white text-sm">
                                <th class="p-3 font-bold border-b">Periodo</th>
                                <th class="p-3 font-bold border-b text-right">Sueldo Base</th>
                                <th class="p-3 font-bold border-b text-center">Días Lab.</th>
                                <th class="p-3 font-bold border-b text-right">H. Extras</th>
                                <th class="p-3 font-bold border-b text-right">Bonif. Ley</th>
                                <th class="p-3 font-bold border-b text-right text-red-300">IGSS</th>
                                <th class="p-3 font-bold border-b text-right text-red-300">ISR</th>
                                <th class="p-3 font-bold border-b text-right text-red-300">Desc. Varios</th>
                                <th class="p-3 font-bold border-b text-right text-green-300">Líquido</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($detalles as $detalle)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border-b font-medium">{{ $detalle->carga->mes_planilla }} {{ $detalle->carga->anio_planilla }}</td>
                                    <td class="p-3 border-b text-right">Q {{ number_format($detalle->sueldo_base, 2) }}</td>
                                    <td class="p-3 border-b text-center">{{ $detalle->dias_laborados }}</td>
                                    <td class="p-3 border-b text-right">Q {{ number_format($detalle->horas_extras, 2) }}</td>
                                    <td class="p-3 border-b text-right">Q {{ number_format($detalle->bonificacion_ley, 2) }}</td>
                                    <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->desc_igss, 2) }}</td>
                                    <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->isr, 2) }}</td>
                                    <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->descuentos_varios, 2) }}</td>
                                    <td class="p-3 border-b text-right font-bold text-green-700">Q {{ number_format($detalle->liquido, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-6 text-center text-gray-500">
                                        Este empleado aún no tiene historial de planillas cargado en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                        @if($detalles->isNotEmpty())
                        <tfoot class="bg-gray-100 font-bold text-sm">
                            <tr>
                                <td class="p-3 text-right uppercase text-gray-700">Totales Acumulados:</td>
                                <td class="p-3 text-right">Q {{ number_format($detalles->sum('sueldo_base'), 2) }}</td>
                                <td class="p-3 text-center">{{ $detalles->sum('dias_laborados') }}</td>
                                <td class="p-3 text-right">Q {{ number_format($detalles->sum('horas_extras'), 2) }}</td>
                                <td class="p-3 text-right">Q {{ number_format($detalles->sum('bonificacion_ley'), 2) }}</td>
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

        </div>
    </div>
</x-app-layout>