<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between items-center">
            <span>{{ __('Detalle de Planilla') }}</span>
            <a href="{{ route('cargas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white text-sm py-1 px-3 rounded">
                Volver
            </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Planilla de {{ $carga->mes_planilla }} {{ $carga->anio_planilla }}</h3>
                    <p class="text-sm text-gray-600">Fecha de carga: {{ \Carbon\Carbon::parse($carga->fecha_carga)->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600">Total registros: <span class="font-bold">{{ $carga->detalleCargas->count() }}</span></p>
                </div>
                
                <div class="flex gap-2">
                    <form action="{{ route('cargas.destroy', $carga->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta carga y todos sus detalles?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                            Eliminar Carga
                        </button>
                    </form>

                    <form action="{{ route('cargas.enviar_notificaciones', $carga->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de enviar las notificaciones a los empleados por correo?')">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded text-sm shadow flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Enviar Notificaciones
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-100 text-sm">
                                <th class="p-3 font-bold text-gray-600 border-b">Código</th>
                                <th class="p-3 font-bold text-gray-600 border-b">Empleado</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right">Sueldo Base</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right">Días Lab.</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right">H. Extras</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right">Bonif. Ley</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right text-red-600">IGSS</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right text-red-600">ISR</th>
                                <th class="p-3 font-bold text-gray-600 border-b text-right text-red-600">Desc. Varios</th>
                                <th class="p-3 font-bold text-gray-800 border-b text-right bg-green-50">Líquido</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($carga->detalleCargas as $detalle)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ $detalle->empleado->codigo_empleado }}</td>
                                    <td class="p-3 border-b">{{ $detalle->empleado->nombres }} {{ $detalle->empleado->apellidos }}</td>
                                    <td class="p-3 border-b text-right">Q {{ number_format($detalle->sueldo_base, 2) }}</td>
                                    <td class="p-3 border-b text-right">{{ $detalle->dias_laborados }}</td>
                                    <td class="p-3 border-b text-right">Q {{ number_format($detalle->horas_extras, 2) }}</td>
                                    <td class="p-3 border-b text-right">Q {{ number_format($detalle->bonificacion_ley, 2) }}</td>
                                    <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->desc_igss, 2) }}</td>
                                    <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->isr, 2) }}</td>
                                    <td class="p-3 border-b text-right text-red-600">Q {{ number_format($detalle->descuentos_varios, 2) }}</td>
                                    <td class="p-3 border-b text-right font-bold bg-green-50">Q {{ number_format($detalle->liquido, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($carga->detalleCargas->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        No se importaron detalles. Verifica que el archivo Excel tenga las columnas correctas y que los códigos de empleado existan en el sistema.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>