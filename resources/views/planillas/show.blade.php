<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Detalle de Planilla: {{ $planilla->codigo }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
                <div>
                    <a href="{{ route('planillas.index') }}" class="text-blue-600 hover:underline text-sm mb-2 inline-block">← Volver al Listado</a>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $planilla->descripcion }}</h3>
                    <p class="text-gray-600">Período: {{ \Carbon\Carbon::parse($planilla->fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($planilla->fecha_fin)->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-500 mt-1">Generada por: {{ $planilla->creador->name ?? 'Sistema' }}</p>
                </div>

                <div class="mt-4 md:mt-0 text-right">
                    <div class="bg-gray-100 p-4 rounded-lg inline-block border border-gray-200">
                        <p class="text-sm text-gray-500 font-bold uppercase">Total a Desembolsar</p>
                        <p class="text-3xl font-black text-indigo-700">Q. {{ number_format($planilla->total_nomina, 2) }}</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <!-- Acciones de Cierre -->
            @if($planilla->estado == 'borrador')
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded mb-6 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-yellow-800">Planilla en Borrador</h4>
                        <p class="text-sm text-yellow-700">Revisa los cálculos. Si todo es correcto, cierra la planilla para aplicar saldos a los empleados.</p>
                    </div>
                    <form action="{{ route('planillas.cerrar', $planilla->id) }}" method="POST" onsubmit="return confirm('ATENCIÓN: Cerrar la planilla es irreversible. Marcará horas extras, bonos e incidencias como PAGADAS. ¿Deseas continuar?');">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-6 rounded shadow text-lg">
                            Cerrar y Aprobar Planilla
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 p-4 rounded mb-6 flex items-center">
                    <span class="text-green-600 text-2xl mr-3">✓</span>
                    <div>
                        <h4 class="font-bold text-green-800">Planilla Cerrada</h4>
                        <p class="text-sm text-green-700">Cerrada el {{ \Carbon\Carbon::parse($planilla->fecha_cierre)->format('d/m/Y H:i') }} por {{ $planilla->cerrador->name ?? 'Sistema' }}</p>
                    </div>
                </div>
            @endif

            <!-- Tabla de Detalles (Recibos) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="p-3 border-b">Empleado</th>
                            <th class="p-3 border-b text-center">Días Trab.</th>
                            <th class="p-3 border-b text-right">Sal. Proporcional</th>
                            <th class="p-3 border-b text-right text-green-300">Ingresos + Extras</th>
                            <th class="p-3 border-b text-right text-red-300">Descuentos + IGSS</th>
                            <th class="p-3 border-b text-right font-bold text-lg">Líquido a Recibir</th>
                            <th class="p-3 border-b text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planilla->detalles as $detalle)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="p-3 font-bold text-gray-700">
                                    {{ $detalle->empleado->primer_nombre }} {{ $detalle->empleado->primer_apellido }}
                                </td>
                                <td class="p-3 text-center font-bold {{ $detalle->dias_trabajados < 30 && $planilla->tipo_periodo == 'mensual' ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $detalle->dias_trabajados }}
                                </td>
                                <td class="p-3 text-right">Q. {{ number_format($detalle->salario_proporcional, 2) }}</td>
                                <td class="p-3 text-right text-green-700">
                                    + Q. {{ number_format($detalle->total_ingresos_adicionales + $detalle->total_horas_extras, 2) }}
                                </td>
                                <td class="p-3 text-right text-red-600">
                                    - Q. {{ number_format($detalle->total_descuentos + $detalle->igss_laboral, 2) }}
                                    <div class="text-xs text-gray-400">(IGSS: Q.{{ number_format($detalle->igss_laboral, 2) }})</div>
                                </td>
                                <td class="p-3 text-right font-bold text-lg text-indigo-700">
                                    Q. {{ number_format($detalle->liquido_a_recibir, 2) }}
                                </td>
                                <td class="p-3 text-center border-b">
                                    <a href="{{ route('planillas.recibo', $detalle->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-bold border border-blue-600 px-3 py-1 rounded hover:bg-blue-50">
                                        Ver Recibo
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>