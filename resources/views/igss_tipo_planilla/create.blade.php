<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Tipo de Planilla') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tipos-planilla.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Empresa *</label>
                            <select name="id_empresa" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione Empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_empresa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Identificación *</label>
                            <input type="number" name="identificacion" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('identificacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre *</label>
                            <input type="text" name="nombre" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Afiliado *</label>
                            <select name="tipo_afiliado" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione</option>
                                <option value="S">S</option>
                                <option value="C">C</option>
                            </select>
                            @error('tipo_afiliado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Periodo *</label>
                            <select name="periodo" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione</option>
                                <option value="M">M</option>
                                <option value="C">C</option>
                                <option value="S">S</option>
                            </select>
                            @error('periodo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Departamento IGSS *</label>
                            <select name="id_igss_departamento" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}">{{ $depto->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_igss_departamento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Actividad Económica IGSS *</label>
                            <select name="id_actividad" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione</option>
                                @foreach($actividades as $actividad)
                                    <option value="{{ $actividad->id }}">{{ $actividad->clasificacion }}</option>
                                @endforeach
                            </select>
                            @error('id_actividad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Clase Planilla *</label>
                            <select name="clase_planilla" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione</option>
                                <option value="N">N</option>
                                <option value="V">V</option>
                            </select>
                            @error('clase_planilla') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tiempo Contrato *</label>
                            <select name="tiempo_contrato" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione</option>
                                <option value="TP">TP</option>
                                <option value="TC">TC</option>
                            </select>
                            @error('tiempo_contrato') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-8 border-t pt-4">
                        <a href="{{ route('tipos-planilla.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Guardar y Generar Cadena</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>