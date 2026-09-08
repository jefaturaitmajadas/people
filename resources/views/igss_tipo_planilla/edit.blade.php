<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Tipo de Planilla') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tipos-planilla.update', $tipo_planilla->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Empresa *</label>
                            <select name="id_empresa" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{ $tipo_planilla->id_empresa == $empresa->id ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_empresa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Identificación *</label>
                            <input type="number" name="identificacion" value="{{ old('identificacion', $tipo_planilla->identificacion) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('identificacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $tipo_planilla->nombre) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Afiliado *</label>
                            <select name="tipo_afiliado" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="S" {{ $tipo_planilla->tipo_afiliado == 'S' ? 'selected' : '' }}>S</option>
                                <option value="C" {{ $tipo_planilla->tipo_afiliado == 'C' ? 'selected' : '' }}>C</option>
                            </select>
                            @error('tipo_afiliado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Periodo *</label>
                            <select name="periodo" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="M" {{ $tipo_planilla->periodo == 'M' ? 'selected' : '' }}>M</option>
                                <option value="C" {{ $tipo_planilla->periodo == 'C' ? 'selected' : '' }}>C</option>
                                <option value="S" {{ $tipo_planilla->periodo == 'S' ? 'selected' : '' }}>S</option>
                            </select>
                            @error('periodo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Departamento IGSS *</label>
                            <select name="id_igss_departamento" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}" {{ $tipo_planilla->id_igss_departamento == $depto->id ? 'selected' : '' }}>{{ $depto->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_igss_departamento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Actividad Económica IGSS *</label>
                            <select name="id_actividad" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @foreach($actividades as $actividad)
                                    <option value="{{ $actividad->id }}" {{ $tipo_planilla->id_actividad == $actividad->id ? 'selected' : '' }}>{{ $actividad->clasificacion }}</option>
                                @endforeach
                            </select>
                            @error('id_actividad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Clase Planilla *</label>
                            <select name="clase_planilla" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="N" {{ $tipo_planilla->clase_planilla == 'N' ? 'selected' : '' }}>N</option>
                                <option value="V" {{ $tipo_planilla->clase_planilla == 'V' ? 'selected' : '' }}>V</option>
                            </select>
                            @error('clase_planilla') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tiempo Contrato *</label>
                            <select name="tiempo_contrato" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="TP" {{ $tipo_planilla->tiempo_contrato == 'TP' ? 'selected' : '' }}>TP</option>
                                <option value="TC" {{ $tipo_planilla->tiempo_contrato == 'TC' ? 'selected' : '' }}>TC</option>
                            </select>
                            @error('tiempo_contrato') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-8 border-t pt-4">
                        <a href="{{ route('tipos-planilla.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Actualizar y Generar Cadena</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>