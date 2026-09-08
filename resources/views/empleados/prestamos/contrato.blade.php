<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 hidden-print text-right mb-4">
        <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
            🖨️ Imprimir Contrato
        </button>
    </div>

    <div class="hoja-carta bg-white shadow-xl mx-auto text-gray-900 leading-relaxed text-justify">
        
        <div class="text-center mb-8">
            <h1 class="text-xl font-black uppercase">{{ $empresa->razon_social ?? $empresa->nombre }}</h1>
            <h2 class="text-lg font-bold uppercase underline">CONTRATO DE PRÉSTAMO Y AUTORIZACIÓN DE DESCUENTO</h2>
        </div>

        <p class="mb-4">
            Yo, <span class="font-bold uppercase">{{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}</span>, con DPI número <span class="font-bold">{{ $empleado->identificacion }}</span>, por este medio hago constar que he solicitado y recibido a mi entera satisfacción un préstamo por la cantidad de <span class="font-bold">Q. {{ number_format($prestamo->monto_solicitado, 2) }}</span> de parte de mi patrono.
        </p>

        <p class="mb-4">
            Acepto que a dicho capital se le adicionará de mutuo acuerdo un 1% en concepto de fondo de ahorro, haciendo un total a descontar de <span class="font-bold">Q. {{ number_format($prestamo->monto_total, 2) }}</span>.
        </p>

        <p class="mb-8">
            Autorizo expresa e irrevocablemente a mi patrono para que descuente de mi salario ordinario, así como de cualquier indemnización, bono 14 o aguinaldo en caso de terminación laboral, las cuotas según el siguiente plan de amortización de frecuencia <span class="font-bold">{{ $prestamo->frecuencia }}</span>:
        </p>

        <!-- Tabla de Amortización -->
        <table class="w-full text-center text-xs border border-gray-900 mb-8">
            <thead class="bg-gray-200 font-bold uppercase">
                <tr>
                    <th class="border border-gray-900 py-1">No.</th>
                    <th class="border border-gray-900 py-1">Fecha de Descuento</th>
                    <th class="border border-gray-900 py-1">Capital (99%)</th>
                    <th class="border border-gray-900 py-1">Ahorro (1%)</th>
                    <th class="border border-gray-900 py-1">Cuota Total a Descontar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestamo->cuotas as $cuota)
                <tr>
                    <td class="border border-gray-900 py-1">{{ $cuota->numero_cuota }}</td>
                    <td class="border border-gray-900 py-1">{{ \Carbon\Carbon::parse($cuota->fecha_aplicacion)->format('d/m/Y') }}</td>
                    <td class="border border-gray-900 py-1">Q. {{ number_format($cuota->monto_capital, 2) }}</td>
                    <td class="border border-gray-900 py-1">Q. {{ number_format($cuota->monto_ahorro, 2) }}</td>
                    <td class="border border-gray-900 py-1 font-bold">Q. {{ number_format($cuota->monto_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="mb-16">
            Para constancia, acepto y firmo el presente documento en la Ciudad de Guatemala a los {{ now()->format('d') }} días del mes de {{ now()->translatedFormat('F') }} de {{ now()->format('Y') }}.
        </p>

        <div class="flex justify-around text-center mt-12 border-t border-transparent">
            <div class="w-64 border-t border-gray-900 pt-2">
                <p class="font-bold uppercase">{{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}</p>
                <p class="text-xs">Firma del Colaborador</p>
                <p class="text-xs">DPI: {{ $empleado->identificacion }}</p>
            </div>
            <div class="w-64 border-t border-gray-900 pt-2">
                <p class="font-bold uppercase">{{ $empresa->firmante_rrhh ?? 'RRHH' }}</p>
                <p class="text-xs">Aprobado por la Empresa</p>
            </div>
        </div>
    </div>

    <style>
        .hoja-carta { width: 21.59cm; min-height: 27.94cm; padding: 2cm 2.5cm; box-sizing: border-box; background: white; font-family: 'Arial', sans-serif; font-size: 11pt; }
        @media print { body * { visibility: hidden; } .hoja-carta, .hoja-carta * { visibility: visible; } .hoja-carta { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; } .hidden-print { display: none !important; } }
    </style>
</x-app-layout>