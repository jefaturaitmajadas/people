<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Editar Experiencia: {{ $experiencia->empleado->primer_nombre }}</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <form action="{{ route('experiencia-laboral.update', $experiencia->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="font-bold text-sm">Empresa *</label><input type="text" name="empresa" value="{{ $experiencia->empresa }}" class="w-full border rounded p-2" required></div>
                    <div><label class="font-bold text-sm">Puesto *</label><input type="text" name="puesto" value="{{ $experiencia->puesto }}" class="w-full border rounded p-2" required></div>
                    
                    <div><label class="font-bold text-sm">Fecha Inicio</label><input type="date" name="fecha_inicio" value="{{ $experiencia->fecha_inicio }}" class="w-full border rounded p-2"></div>
                    <div><label class="font-bold text-sm">Fecha Fin</label><input type="date" name="fecha_fin" value="{{ $experiencia->fecha_fin }}" class="w-full border rounded p-2"></div>
                    
                    <div><label class="font-bold text-sm">Teléfono Empresa</label><input type="text" name="telefono_empresa" value="{{ $experiencia->telefono_empresa }}" class="w-full border rounded p-2"></div>
                    <div><label class="font-bold text-sm">Dirección Empresa</label><input type="text" name="direccion_empresa" value="{{ $experiencia->direccion_empresa }}" class="w-full border rounded p-2"></div>

                    <div><label class="font-bold text-sm">Primer Salario</label><input type="text" name="primer_salario" value="{{ $experiencia->primer_salario }}" class="w-full border rounded p-2"></div>
                    <div><label class="font-bold text-sm">Segundo Salario</label><input type="text" name="segundo_salario" value="{{ $experiencia->segundo_salario }}" class="w-full border rounded p-2"></div>

                    <div class="col-span-2"><label class="font-bold text-sm">Motivo de Retiro</label><textarea name="motivo_retiro" rows="2" class="w-full border rounded p-2">{{ $experiencia->motivo_retiro }}</textarea></div>
                    <div class="col-span-2"><label class="font-bold text-sm">Observaciones</label><textarea name="observaciones" rows="2" class="w-full border rounded p-2">{{ $experiencia->observaciones }}</textarea></div>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <a href="{{ route('experiencia-laboral.index', $experiencia->empleado_id) }}" class="bg-gray-500 text-white py-2 px-4 rounded">Cancelar</a>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Actualizar Experiencia</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>