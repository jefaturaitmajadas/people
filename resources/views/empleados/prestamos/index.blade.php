<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Gestión de Anticipo: <span class="text-indigo-600">{{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}</span>
            </h2>
            <a href="{{ route('empleados.edit', $empleado->id) }}" class="text-gray-600 hover:text-gray-900 font-bold text-sm">
                ← Volver al Expediente
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 font-bold">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 font-bold">Error en la solicitud. Verifica los montos.</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- PANEL IZQUIERDO: Tarjeta de Disponibilidad y Formulario -->
            <div class="md:col-span-1 space-y-6">
                
                <!-- Disponibilidad -->
                <div class="bg-indigo-900 text-white p-6 rounded-lg shadow">
                    <h3 class="font-black mb-4 uppercase text-sm border-b border-indigo-700 pb-2">Capacidad de Endeudamiento</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-300">Días Laborados:</span>
                            <span class="font-bold">{{ $disponibilidad['dias_laborados'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-300">Proyección Total:</span>
                            <span class="font-bold">Q. {{ number_format($disponibilidad['disponible_bruto'] / 0.70, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-300">Garantía Bruta (70%):</span>
                            <span class="font-bold">Q. {{ number_format($disponibilidad['disponible_bruto'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-pink-300">Anticipos Activos:</span>
                            <span class="font-bold">- Q. {{ number_format($disponibilidad['capital_pendiente'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-indigo-700 mt-2">
                            <span class="font-black uppercase text-indigo-200">Disponible Neto:</span>
                            <span class="font-black text-2xl text-green-400">
                                Q. {{ number_format($disponibilidad['disponible_neto'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold text-gray-700 mb-4 uppercase text-sm border-b pb-2">Generar Nuevo Anticipo</h3>
                    
                    <form action="{{ route('empleados.prestamos.store', $empleado->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Monto a Solicitar (Q) *</label>
                            <input type="number" step="0.01" min="1" max="{{ $disponibilidad['disponible_neto'] }}" name="monto_solicitado" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            <p class="text-[10px] text-gray-500 mt-1">No puede exceder el disponible neto.</p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Frecuencia de Descuento *</label>
                            <select name="frecuencia" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                <option value="Mensual">Mensual (Días 1)</option>
                                <option value="Quincenal">Quincenal (Días 1 y 16)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold text-xs text-gray-700 mb-1">Cantidad de Cuotas *</label>
                            <input type="number" name="cantidad_cuotas" min="1" max="48" class="w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Max 24 (Mensual) o 48 (Quincenal)" required>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow transition-colors" @if($disponibilidad['disponible_neto'] <= 0) disabled @endif>
                            Calcular y Generar Borrador
                        </button>
                    </form>
                </div>
            </div>

            <!-- PANEL DERECHO: Historial -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-gray-700 uppercase text-sm">Historial de Anticipos</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600">
                                    <th class="p-3 font-bold border-b">Fecha Solicitud</th>
                                    <th class="p-3 font-bold border-b">Monto Solicitado</th>
                                    <th class="p-3 font-bold border-b">Ahorro (1%)</th>
                                    <th class="p-3 font-bold border-b text-center">Cuotas</th>
                                    <th class="p-3 font-bold border-b text-center">Estado</th>
                                    <th class="p-3 font-bold border-b text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prestamos as $p)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-3">{{ $p->created_at->format('d/m/Y') }}</td>
                                        <td class="p-3 font-bold text-gray-800">Q. {{ number_format($p->monto_solicitado, 2) }}</td>
                                        <td class="p-3 text-indigo-600">Q. {{ number_format($p->monto_total - $p->monto_solicitado, 2) }}</td>
                                        <td class="p-3 text-center">{{ $p->frecuencia }} ({{ $p->cantidad_cuotas }})</td>
                                        <td class="p-3 text-center">
                                            @if($p->estado == 'Borrador')
                                                <span class="bg-amber-100 text-amber-800 py-1 px-2 rounded text-xs font-bold">Pendiente Firma</span>
                                            @elseif($p->estado == 'Activo')
                                                <span class="bg-green-100 text-green-800 py-1 px-2 rounded text-xs font-bold">Activo</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 py-1 px-2 rounded text-xs font-bold">Pagado</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-center flex justify-center gap-2">
                                            <a href="{{ route('prestamos.contrato', $p->id) }}" target="_blank" class="text-blue-600 hover:underline font-bold text-xs">Contrato</a>
                                            
                                            @if($p->estado == 'Borrador')
                                                <!-- Botón Aprobar -->
                                                <form action="{{ route('prestamos.aprobar', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:underline font-bold text-xs" onclick="return confirm('¿El empleado ya firmó el contrato?')">Aprobar</button>
                                                </form>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('prestamos.destroy', $p->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline font-bold text-xs" onclick="return confirm('¿Seguro que deseas eliminar este préstamo? Esta acción no se puede deshacer.')">Eliminar</button>
                                                </form>
                                            @else
                                                <a href="{{ route('prestamos.estado_cuenta', $p->id) }}" target="_blank" class="text-indigo-600 hover:underline font-bold text-xs">Estado de Cuenta</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="p-6 text-center text-gray-500">Sin préstamos registrados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>