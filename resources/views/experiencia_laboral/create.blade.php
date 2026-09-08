<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Registrar Experiencia para: {{ $empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <form action="{{ route('experiencia-laboral.store', $empleado->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="font-bold text-sm">Empresa *</label><input type="text" name="empresa" class="w-full border rounded p-2" required></div>
                    <div><label class="font-bold text-sm">Puesto *</label><input type="text" name="puesto" class="w-full border rounded p-2" required></div>
                    
                    <div><label class="font-bold text-sm">Fecha Inicio</label><input type="date" name="fecha_inicio" class="w-full border rounded p-2"></div>
                    <div><label class="font-bold text-sm">Fecha Fin</label><input type="date" name="fecha_fin" class="w-full border rounded p-2"></div>
                    
                    <div><label class="font-bold text-sm">Teléfono Empresa</label><input type="text" name="telefono_empresa" class="w-full border rounded p-2"></div>
                    <div><label class="font-bold text-sm">Dirección Empresa</label><input type="text" name="direccion_empresa" class="w-full border rounded p-2"></div>

                    <div><label class="font-bold text-sm">Primer Salario</label><input type="text" name="primer_salario" placeholder="Ej: Q. 3,500.00" class="w-full border rounded p-2"></div>
                    <div><label class="font-bold text-sm">Segundo Salario</label><input type="text" name="segundo_salario" placeholder="Ej: Q. 4,000.00" class="w-full border rounded p-2"></div>

                    <div class="col-span-2"><label class="font-bold text-sm">Motivo de Retiro</label><textarea name="motivo_retiro" rows="2" class="w-full border rounded p-2"></textarea></div>
                    <div class="col-span-2"><label class="font-bold text-sm">Observaciones</label><textarea name="observaciones" rows="2" class="w-full border rounded p-2"></textarea></div>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <a href="{{ route('experiencia-laboral.index', $empleado->id) }}" class="bg-gray-500 text-white py-2 px-4 rounded">Cancelar</a>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Guardar Experiencia</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>