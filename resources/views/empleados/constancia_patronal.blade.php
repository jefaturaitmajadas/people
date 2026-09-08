<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 hidden-print text-right mb-4">
        <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-blue-600 hover:underline mr-4 font-bold">← Volver al Expediente</a>
        <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow">
            🖨️ Imprimir / Guardar PDF
        </button>
    </div>

    <!-- Contenedor tamaño Carta -->
    <div class="hoja-carta bg-white shadow-xl mx-auto">
        
        <!-- Encabezado -->
        <div class="text-center mb-12">
            @if($empresa->logo)
                <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo" class="h-20 mx-auto mb-4 object-contain">
            @endif
            
            <h2 class="text-xl font-bold mt-4 uppercase decoration-2 underline-offset-4 text-gray-900">
                CONSTANCIA LABORAL CON NÚMERO PATRONAL
            </h2>
        </div>

        <div class="mb-8 text-center">
            <p class="font-bold text-gray-900">A QUIEN INTERESE:</p>
        </div>

        <!-- Cuerpo de la Carta (con datos patronales) -->
        <div class="text-justify leading-relaxed mb-8 text-gray-900">
             <p class="mb-4">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Por medio de la presente se hace constar que 
           
                <span class="font-bold uppercase">{{ $empleado->primer_nombre }} {{ $empleado->segundo_nombre }} {{ $empleado->primer_apellido }} {{ $empleado->segundo_apellido }}</span> 
                quien se identifica con Documento Personal de Identificación (DPI) CUI número 
                <span class="font-bold">{{ $empleado->identificacion ?? '__________' }}</span> , 
                
                @if($fechaBaja)
                    laboró para la empresa desde el <span class="font-bold">{{ $fechaInicio }}</span> al <span class="font-bold">{{ $fechaBaja }}</span>,
                @else
                    labora para la empresa desde el <span class="font-bold">{{ $fechaInicio }}</span>,
                @endif
                
                desempeñando el puesto de <span class="font-bold uppercase">{{ $empleado->puesto->nombre ?? 'N/A' }}</span>, devengando un salario de 
                <span class="font-bold">Q.{{ number_format($salarioBase, 2) }} ({{ $salarioLetras }} QUETZALES CON {{ $centavos }}/100)</span> 
                más Q.250.00 de bonificación incentivo de ley, con el número de afiliación {{ $empleado->no_igss ?? 'N/A' }}
            </p>

            <p>
                Razón Social: {{ $empresa->razon_social ?? ''}} <br>
                Número Patronal: {{ $empresa->numero_patronal ?? ''}} <br>

            </p>
            <br>
            
            <p>
                Para los usos legales y administrativos que al interesado convengan, se extiende, firma y sella la presente a los 
                {{ $diaLetras }} días del mes de {{ ucfirst($mesActual) }} de {{ $anioActual }}.
            </p>
        </div>

        <!-- Firmas -->
        <div class="mt-24">
            <p class="mb-12">Atentamente,</p>
            <div class="text-center">
                <p class="font-bold">{{ $empresa->firmante_rrhh ?? '___________________________' }}</p>
                <p>Recursos Humanos</p>
                
            </div>
        </div>

        <!-- Footer -->
        <div class="pie-pagina text-center text-xs text-gray-600 mt-20 border-t border-gray-300 pt-4">
            <p class="uppercase">{{ $empresa->razon_social ?? 'RAZON NO REGISTRADA' }}</p>
            <p class="uppercase">{{ $empresa->direccion ?? 'DIRECCIÓN NO REGISTRADA' }}</p>
            <p>TELS: {{ $empresa->telefono ?? 'N/A' }} | EMAIL: {{ $empresa->correo ?? 'N/A' }}</p>
            
        </div>
    </div>

    <!-- Estilos de Impresión -->
    <style>
        .hoja-carta {
            width: 21.59cm;
            min-height: 27.94cm;
            padding: 3cm 2.5cm;
            box-sizing: border-box;
            background: white;
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
        }
        @media print {
            body * { visibility: hidden; }
            .hoja-carta, .hoja-carta * { visibility: visible; }
            .hoja-carta {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 1.5cm;
                box-shadow: none;
            }
            .hidden-print { display: none !important; }
            .pie-pagina {
                position: fixed;
                bottom: 1cm;
                width: calc(100% - 3cm);
            }
        }
    </style>
</x-app-layout>