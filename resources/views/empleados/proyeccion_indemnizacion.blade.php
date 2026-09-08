<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 hidden-print text-right mb-4">
        <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-blue-600 hover:underline mr-4 font-bold">← Volver al Expediente</a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
            🖨️ Imprimir / Guardar PDF
        </button>
    </div>

    <!-- Contenedor tamaño Carta -->
    <div class="hoja-carta bg-white shadow-xl mx-auto text-gray-900">
        
        <!-- Encabezado -->
        <div class="text-center mb-8 border-b-2 border-gray-800 pb-6">
            @if($empresa->logo)
                <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo" class="h-16 mx-auto mb-2 object-contain">
            @endif
            <h1 class="text-2xl font-black uppercase">{{ $empresa->razon_social ?? $empresa->nombre }}</h1>
            <h2 class="text-lg font-bold mt-2 uppercase text-gray-700">Reporte de Proyección de Indemnización</h2>
            <p class="text-sm mt-1 text-gray-500">Documento interno de Recursos Humanos</p>
        </div>

        <!-- Datos del Colaborador -->
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded">
            <h3 class="font-bold border-b border-gray-300 pb-2 mb-4 uppercase text-sm">1. Datos del Colaborador</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="font-bold">Nombre:</span> {{ mb_strtoupper($empleado->primer_nombre . ' ' . $empleado->primer_apellido) }}</div>
                <div><span class="font-bold">DPI:</span> {{ $empleado->identificacion ?? 'N/A' }}</div>
                <div><span class="font-bold">Puesto:</span> {{ mb_strtoupper($empleado->puesto->nombre ?? 'N/A') }}</div>
                <div><span class="font-bold">Departamento:</span> {{ mb_strtoupper($empleado->departamento->nombre ?? 'N/A') }}</div>
            </div>
        </div>

        <!-- Datos de Tiempo -->
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded">
            <h3 class="font-bold border-b border-gray-300 pb-2 mb-4 uppercase text-sm">2. Período Laborado</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="font-bold">Fecha de Ingreso:</span> {{ $fechaInicioTxt }}</div>
                <div>
                    <span class="font-bold">Fecha de Proyección (Salida):</span> {{ $fechaCalculoTxt }} 
                    @if(!$empleado->fecha_baja) <span class="italic text-xs text-gray-500">(Al día de hoy)</span> @endif
                </div>
                <div class="col-span-2 text-center mt-2 bg-gray-200 p-2 rounded">
                    <span class="font-bold">Tiempo Total:</span> {{ $tiempoFormateado }} <br>
                    <span class="text-xs font-bold text-gray-600">({{ number_format($diasLaborados) }} días exactos)</span>
                </div>
            </div>
        </div>

        <!-- Integración Salarial -->
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded">
            <h3 class="font-bold border-b border-gray-300 pb-2 mb-4 uppercase text-sm">3. Integración de Salario Promedio</h3>
            <table class="w-full text-sm text-left">
                <tbody>
                    <tr>
                        <td class="py-1">Salario Base Mensual</td>
                        <td class="py-1 text-right">Q. {{ number_format($salarioBase, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Proporción Mensual Bono 14 (1/12)</td>
                        <td class="py-1 text-right">Q. {{ number_format($proporcionBono14, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 border-b border-gray-300">Proporción Mensual Aguinaldo (1/12)</td>
                        <td class="py-1 text-right border-b border-gray-300">Q. {{ number_format($proporcionAguinaldo, 2) }}</td>
                    </tr>
                    <tr class="font-bold bg-gray-200">
                        <td class="py-2 px-2">SALARIO PROMEDIO INTEGRADO</td>
                        <td class="py-2 px-2 text-right">Q. {{ number_format($salarioIntegrado, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Liquidación Final -->
        <div class="mb-8 p-4 border-2 border-indigo-900 rounded bg-indigo-50">
            <h3 class="font-black text-indigo-900 pb-2 uppercase text-center text-lg">Indemnización Proyectada</h3>
            <p class="text-center text-xs text-indigo-700 mb-4">(Salario Integrado ÷ 365) × Días Laborados</p>
            
            <div class="text-center">
                <span class="text-3xl font-black text-gray-900">Q. {{ number_format($indemnizacionTotal, 2) }}</span>
            </div>
        </div>

        <!-- Firmas -->
        <div class="mt-20">
            <div class="text-center w-64 mx-auto border-t border-gray-900 pt-2">
                <p class="font-bold text-sm">{{ Auth::user()->name }}</p>
                <p class="text-xs">Generado por Recursos Humanos</p>
            </div>
        </div>

    </div>

    <!-- Estilos -->
    <style>
        .hoja-carta {
            width: 21.59cm;
            min-height: 27.94cm;
            padding: 2cm;
            box-sizing: border-box;
            background: white;
            font-family: 'Arial', sans-serif;
        }
        @media print {
            body * { visibility: hidden; }
            .hoja-carta, .hoja-carta * { visibility: visible; }
            .hoja-carta {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            .hidden-print { display: none !important; }
        }
    </style>
</x-app-layout>