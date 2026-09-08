<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Generar Cálculo de Planilla</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <strong>Información:</strong> Al hacer clic en "Generar Borrador", el Motor de Nómina calculará sueldos, descuentos, horas extras e IGSS basado en este rango de fechas. 
                    <br>Esto se guardará como <strong>Borrador</strong> para tu revisión. Las cuentas de los empleados no se verán afectadas hasta que apruebes y cierres la planilla.
                </p>
            </div>

            <!-- ALERTAS DE ERROR (Pegar justo arriba del form) -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong class="font-bold">Por favor revisa los siguientes errores:</strong>
                    <ul class="list-disc pl-5 mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong>Error del Motor:</strong> {{ session('error') }}
                </div>
            @endif
            <!-- FIN DE ALERTAS -->
             
            <form action="{{ route('planillas.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Descripción de la Planilla *</label>
                    <input type="text" name="descripcion" class="w-full border rounded p-2" placeholder="Ej: Segunda Quincena de Julio 2026" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Tipo de Período *</label>
                        <select name="tipo_periodo" class="w-full border rounded p-2" required>
                            <option value="mensual">Mensual (30 días)</option>
                            <option value="quincenal">Quincenal (15 días)</option>
                            <option value="semanal">Semanal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Inicio *</label>
                        <input type="date" name="fecha_inicio" class="w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Fecha de Fin *</label>
                        <input type="date" name="fecha_fin" class="w-full border rounded p-2" required>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('planillas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white py-2 px-4 rounded shadow">Ejecutar Motor (Generar Borrador)</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>