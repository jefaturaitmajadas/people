<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Control de Vacaciones: <span class="text-indigo-600">{{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}</span>
            </h2>
            <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-gray-600 hover:text-gray-900 font-bold text-sm">
                ← Volver al Expediente
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Alertas -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 font-bold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 font-bold">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- PANEL IZQUIERDO: Resumen y Formulario -->
            <div class="md:col-span-1 space-y-6">
                
                <!-- Tarjeta de Saldos -->
                <div class="bg-white p-6 rounded-lg shadow border-t-4 border-indigo-500">
                    <h3 class="font-black text-gray-700 mb-4 uppercase text-sm border-b pb-2">Resumen de Saldo</h3>
                    
                    @if($calculo['es_colapersa'])
                        <div class="bg-blue-50 text-blue-800 text-xs p-2 rounded mb-4 border border-blue-200">
                            <strong>Aviso:</strong> Cálculo especial aplicado (Colapersa). Fecha base de cálculo: {{ $calculo['fecha_base'] }}.
                        </div>
                    @endif

                    <div class="space-y-3 text-sm">
                        <!-- Acumulados por Ley (Se resta el bono para mostrar solo lo generado por tiempo) -->
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Días Acumulados (Ley):</span>
                            <span class="font-bold text-gray-900">{{ $calculo['dias_acumulados'] - ($empleado->dias_adicionales_vacaciones ?? 0) }}</span>
                        </div>
                        
                        <!-- Mostramos los días adicionales solo si tiene más de 0 -->
                        @if(($empleado->dias_adicionales_vacaciones ?? 0) > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Días Adicionales (Bono):</span>
                                <span class="font-bold text-indigo-600">+ {{ $empleado->dias_adicionales_vacaciones }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Días Tomados:</span>
                            <span class="font-bold text-red-600">- {{ $calculo['dias_tomados'] }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="font-black text-gray-800 uppercase">Saldo Disponible:</span>
                            <span class="font-black text-2xl {{ $calculo['saldo_disponible'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $calculo['saldo_disponible'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Registro -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold text-gray-700 mb-4 uppercase text-sm border-b pb-2">Registrar Descanso</h3>
                    
                    <form action="{{ route('empleados.vacaciones.store', $empleado->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Fecha de Fin *</label>
                            <input type="date" name="fecha_fin" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Días a Descontar *</label>
                            <!-- El step="0.5" permite medios días -->
                            <input type="number" step="0.5" min="0.5" name="dias_tomados" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Ej: 1, 1.5, 0.5" required>
                            <p class="text-[10px] text-gray-500 mt-1">Usa decimales como 0.5 para medios días.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Observaciones (Opcional)</label>
                            <textarea name="observaciones" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Ej: Aprobado por el jefe inmediato..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                            Guardar Registro
                        </button>
                    </form>
                </div>
            </div>

            <!-- PANEL DERECHO: Tabla de Historial -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-700 uppercase text-sm">Historial de Vacaciones Tomadas</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600">
                                    <th class="p-3 font-bold border-b">Período del Descanso</th>
                                    <th class="p-3 font-bold border-b text-center">Días Tomados</th>
                                    <th class="p-3 font-bold border-b">Observaciones</th>
                                    <th class="p-3 font-bold border-b text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vacaciones as $v)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-3 text-gray-800">
                                            Del <strong>{{ \Carbon\Carbon::parse($v->fecha_inicio)->format('d/m/Y') }}</strong> <br>
                                            al <strong>{{ \Carbon\Carbon::parse($v->fecha_fin)->format('d/m/Y') }}</strong>
                                        </td>
                                        <td class="p-3 text-center font-black text-red-600">
                                            {{ $v->dias_tomados }}
                                        </td>
                                        <td class="p-3 text-gray-600 text-xs">
                                            {{ $v->observaciones ?? '-' }}
                                        </td>
                                        <td class="p-3 text-center">
                                            <form action="{{ route('empleados.vacaciones.destroy', $v->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro? Esto devolverá el saldo al empleado.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-800 font-bold text-xs bg-red-50 px-2 py-1 rounded border border-red-200">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-gray-500">Este colaborador aún no tiene registros de vacaciones tomadas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>