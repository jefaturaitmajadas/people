<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Reporte del Empleador
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Controles y Filtros (No se imprimen) -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 hidden-print">
            <form action="{{ route('reportes.empleador') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">
                
                <div class="flex items-center gap-4">
                    <label for="anio" class="font-bold text-gray-700">Seleccionar Año de Ejercicio:</label>
                    <select name="anio" id="anio" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($aniosDisponibles as $anio)
                            <option value="{{ $anio }}" {{ $anioSeleccionado == $anio ? 'selected' : '' }}>
                                {{ $anio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="window.print()" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded shadow transition-colors">
                    🖨️ Imprimir Reporte
                </button>
            </form>
        </div>

        <!-- Área del Reporte Imprimible -->
        <div class="area-reporte bg-white p-8 rounded-lg shadow-xl text-gray-900">
            
            <!-- Encabezado del Reporte -->
            <div class="text-center mb-8 border-b-2 border-gray-800 pb-4">
                <h1 class="text-2xl font-black uppercase">{{ $empresa->razon_social ?? $empresa->nombre }}</h1>
                <h2 class="text-lg font-bold mt-1 uppercase text-gray-700">REPORTE ANUAL DEL EMPLEADOR</h2>
                <p class="font-bold text-indigo-700 mt-2 text-xl">EJERCICIO: {{ $anioSeleccionado }}</p>
                <p class="text-sm text-gray-500 mt-1">NÚMERO PATRONAL IGSS: {{ $empresa->numero_patronal ?? 'N/A' }}</p>
            </div>

            <!-- Resumen -->
            <div class="mb-6 bg-gray-50 p-4 border rounded">
                <p class="text-sm font-bold">Total de colaboradores que estuvieron activos en este período: <span class="text-indigo-600 text-lg">{{ $empleados->count() }}</span></p>
            </div>

            <!-- Tabla de Empleados -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-800 uppercase text-xs">
                            <th class="p-3 border border-gray-300 font-bold">No.</th>
                            <th class="p-3 border border-gray-300 font-bold">Primer Nombre</th>
                            <th class="p-3 border border-gray-300 font-bold">Segundo Nombre</th>
                            <th class="p-3 border border-gray-300 font-bold">Primer Apellido</th>
                            <th class="p-3 border border-gray-300 font-bold">Segundo Apellido</th>
                            <th class="p-3 border border-gray-300 font-bold">Apellido de casada</th>
                            <th class="p-3 border border-gray-300 font-bold">Nacionalidad</th>
                            <th class="p-3 border border-gray-300 font-bold">Tipo de discapacidad</th>
                            <th class="p-3 border border-gray-300 font-bold">Estado Civil</th>
                            <th class="p-3 border border-gray-300 font-bold">Documento identificación (DPI, Pasaporte u otro)</th>
                            <th class="p-3 border border-gray-300 font-bold">Número de documento</th>
                            <th class="p-3 border border-gray-300 font-bold">Pais de origen</th>
                            <th class="p-3 border border-gray-300 font-bold">Número de expediente del permiso de extranjero</th>
                            <th class="p-3 border border-gray-300 font-bold">Lugar de nacimiento (municipio)</th>
                            <th class="p-3 border border-gray-300 font-bold">Número de indentificación Tributaria (NIT)</th>
                            <th class="p-3 border border-gray-300 font-bold">Número de afiliación IGSS</th>
                            <th class="p-3 border border-gray-300 font-bold">Sexo</th>
                            <th class="p-3 border border-gray-300 font-bold">Fecha de Nacimiento</th>
                            <th class="p-3 border border-gray-300 font-bold">Nivel académico más alto alcanzado</th>
                            <th class="p-3 border border-gray-300 font-bold">Título o diploma (profesión)</th>
                            <th class="p-3 border border-gray-300 font-bold">Pueblo de pertenencia</th>
                            <th class="p-3 border border-gray-300 font-bold">Comunidad Lingüística</th>
                            <th class="p-3 border border-gray-300 font-bold">Cantidad de Hijos</th>
                            <th class="p-3 border border-gray-300 font-bold">Temporalidad del Contrato</th>
                            <th class="p-3 border border-gray-300 font-bold">Tipo Contrato</th>
                            <th class="p-3 border border-gray-300 font-bold">Fecha de inicio de labores</th>
                            <th class="p-3 border border-gray-300 font-bold">Fecha de reinicio de labores</th>
                            <th class="p-3 border border-gray-300 font-bold">Fecha de finalización de labores</th>
                            <th class="p-3 border border-gray-300 font-bold">Ocupación (puesto)</th>
                            <th class="p-3 border border-gray-300 font-bold">Jornada de trabajo</th>
                            <th class="p-3 border border-gray-300 font-bold">Días laborados en el año</th>
                            <th class="p-3 border border-gray-300 font-bold">Salario mensual nominal</th>
                            <th class="p-3 border border-gray-300 font-bold">Salario anual nominal</th>
                            <th class="p-3 border border-gray-300 font-bold">Bonificación Decreto 78-89  (Q.250.00)</th>
                            <th class="p-3 border border-gray-300 font-bold">Total Horas Extras Anuales</th>
                            <th class="p-3 border border-gray-300 font-bold">Valor de la hora extra</th>
                            
                            
                            <th class="p-3 border border-gray-300 font-bold">Monto Aguinaldo Decreto 76-78</th>
                            <th class="p-3 border border-gray-300 font-bold">Monto Bono 14  Decreto 42-92</th>
                            <th class="p-3 border border-gray-300 font-bold">Retribución por comisiones</th>
                            <th class="p-3 border border-gray-300 font-bold">Viáticos</th>
                            <th class="p-3 border border-gray-300 font-bold">Bonificaciones Adicionales</th>
                            <th class="p-3 border border-gray-300 font-bold">Retribución por vacaciones</th>
                            <th class="p-3 border border-gray-300 font-bold">Retribución por indemnización (Artículo 82 Código de Trabajo)</th>
                            <th class="p-3 border border-gray-300 font-bold">Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $index => $empleado)
                            @php
                                $bajaEsteAnio = $empleado->fecha_salida && \Carbon\Carbon::parse($empleado->fecha_salida)->year == $anioSeleccionado;
                                $bajaAnterior = $empleado->fecha_salida && \Carbon\Carbon::parse($empleado->fecha_salida)->year < $anioSeleccionado;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 border border-gray-300 text-center">{{ $index + 1 }}</td>

                                <td class="p-2 border border-gray-300">{{ $empleado->primer_nombre ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->segundo_nombre ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->primer_apellido ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->segundo_apellido ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->casada_apellido ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->paisNacimiento->emplNacionalidad->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->discapacidad->emplTipoDiscapacidad->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->estadoCivil->emplEstadoCivil->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->tipoIdentificacion->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->identificacion ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->paisNacimiento->emplNacionalidad->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->municipioNacimiento->codigo_empl ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ str_replace('-', '', $empleado->nit ?? '') }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->no_igss ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->genero->emplSexo->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->fecha_nacimiento ? \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') : '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->nivelEducativo->emplNivelEducativo->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->profesion->nombre ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->pueblo->emplPuebloPertenencia->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->comunidadLinguistica->emplComunidadLinguistica->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->cantidad_hijos ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->tipoContrato->emplTemporalidadContrato->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ '2' }}</td> <!-- Tipo de Contrato Todos son escritos 2 -->
                                <td class="p-2 border border-gray-300">{{ $empleado->fecha_inicio ? \Carbon\Carbon::parse($empleado->fecha_inicio)->format('d/m/Y') : '' }}</td>
                                <td class="p-2 border border-gray-300">{{ '' }}</td> <!-- Fecha de reinicio de labores -->
                                <td class="p-2 border border-gray-300">{{ $empleado->fecha_salida ? \Carbon\Carbon::parse($empleado->fecha_salida)->format('d/m/Y') : '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->ocupacion->empleOcupacion->codigo ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->turno->emplJornadaTrabajo->codigo ?? '' }}</td>

                                
                                
                                
                                @php
                                    // 1. Límites del año seleccionado
                                    $inicioAnio = \Carbon\Carbon::parse($anioSeleccionado . '-01-01');
                                    $finAnio = \Carbon\Carbon::parse($anioSeleccionado . '-12-31');

                                    // 2. Fechas del empleado
                                    $inicioEmpleado = \Carbon\Carbon::parse($empleado->fecha_inicio);
                                    $finEmpleado = $empleado->fecha_salida ? \Carbon\Carbon::parse($empleado->fecha_salida) : null;

                                    // 3. Determinar desde cuándo empezamos a contar en ESTE año
                                    $fechaCalculoInicio = $inicioEmpleado->greaterThan($inicioAnio) ? $inicioEmpleado : $inicioAnio;

                                    // 4. Determinar hasta cuándo contamos en ESTE año
                                    $fechaCalculoFin = ($finEmpleado && $finEmpleado->lessThan($finAnio)) ? $finEmpleado : $finAnio;

                                    // 5. Calcular los días
                                    if ($fechaCalculoInicio->lessThanOrEqualTo($fechaCalculoFin)) {
                                        // diffInDays + 1 hace que el conteo sea inclusivo (ej: del 1 al 2 de enero son 2 días)
                                        $diasLaborados = $fechaCalculoInicio->diffInDays($fechaCalculoFin) + 1;
                                    } else {
                                        $diasLaborados = 0;
                                    }
                                @endphp
                                <td class="p-2 border border-gray-300 text-center font-bold text-gray-700">
                                    {{ $diasLaborados }}
                                </td>
                                <td class="p-2 border border-gray-300">{{ $empleado->salario ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ $empleado->salario * 12 ?? '' }}</td>
                                <td class="p-2 border border-gray-300">{{ '250' }}</td>
                                <!-- Columna de Suma de Horas Extras -->
                                <td class="p-2 border border-gray-300 ">
                                    

                                    {{ $empleado->planillas_detalles_sum_total_horas_extras > 0 ? $empleado->planillas_detalles_sum_total_horas_extras : '' }}
                                </td>
                                
                                
                                
                                <td class="p-2 border border-gray-300">{{ ((($empleado->salario / 30)/8)*1.5) ?? 'N/A' }}</td>
                               
                                @php
                                    // 2. Fechas del empleado
                                    $inicioEmpleado = \Carbon\Carbon::parse($empleado->fecha_inicio);
                                    $finEmpleado = $empleado->fecha_salida ? \Carbon\Carbon::parse($empleado->fecha_salida) : \Carbon\Carbon::parse($anioSeleccionado . '-11-30');

                                    $dias = $inicioEmpleado->diffInDays($finEmpleado) + 1;
                                    //dd($inicioEmpleado, $finEmpleado, $dias);
                                    if($dias > 365){
                                        $aguinaldo = $empleado->salario;
                                    }else{
                                        $aguinaldo = ($empleado->salario / 365) * $dias;
                                    }
                                @endphp
                                <td class="p-2 border border-gray-300 text-center font-bold text-gray-700">
                                    {{ $aguinaldo }}
                                </td>

                                @php
                                    // 2. Fechas del empleado
                                    $inicioEmpleado = \Carbon\Carbon::parse($empleado->fecha_inicio);
                                    $finEmpleado = $empleado->fecha_salida ? \Carbon\Carbon::parse($empleado->fecha_salida) : \Carbon\Carbon::parse($anioSeleccionado . '-06-30');

                                    $dias = $inicioEmpleado->diffInDays($finEmpleado) + 1;
                                    //dd($inicioEmpleado, $finEmpleado, $dias);
                                    if($dias > 365){
                                        $bono14 = $empleado->salario;
                                    }else{
                                        $bono14 = ($empleado->salario / 365) * $dias;
                                    }
                                @endphp
                                <td class="p-2 border border-gray-300 text-center font-bold text-gray-700">
                                    {{ $bono14 }}
                                </td>

                                <td class="p-2 border border-gray-300 ">
                                    

                                    {{ $empleado->planillas_detalles_sum_total_horas_extras > 0 ? $empleado->planillas_detalles_sum_total_horas_extras : '' }}
                                </td>
                                <td class="p-2 border border-gray-300 ">
                                    

                                    {{ '0' }}
                                </td>
                                <td class="p-2 border border-gray-300 ">
                                    

                                    {{ $empleado->planillas_detalles_sum_total_ingresos_adicionales > 0 ? $empleado->planillas_detalles_sum_total_ingresos_adicionales : '' }}
                                </td>
                                <td class="p-2 border border-gray-300 ">
                                    

                                    {{ '0' }}
                                </td>
                                <td class="p-2 border border-gray-300 ">
                                    {{ '0' }}
                                </td>
                                <td class="p-2 border border-gray-300 ">
                                    

                                    {{ '' }}
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="p-6 text-center text-gray-500 font-bold border border-gray-300">
                                    No se encontraron empleados que hayan laborado durante el año {{ $anioSeleccionado }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-12 hidden-print">
                <p class="text-xs text-gray-400">Fin del reporte.</p>
            </div>
        </div>
    </div>

    <!-- Estilos de Impresión -->
    <style>
        @media print {
            body * { visibility: hidden; }
            .area-reporte, .area-reporte * { visibility: visible; }
            .area-reporte {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .hidden-print { display: none !important; }
            table { font-size: 10pt; }
        }
    </style>
</x-app-layout>