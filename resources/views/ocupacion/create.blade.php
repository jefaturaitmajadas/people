<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Ocupación del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('ocupacion.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Ocupación *</label>
                        <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" placeholder="Ej: Gerente General, Asesor de Ventas" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 mb-4 border-t pt-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Asignar a Ocupación del Empleador *</label>
                            <select name="id_emple_ocupacion" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione una opción</option>
                                @foreach($empleOcupaciones as $emple)
                                    <option value="{{ $emple->id }}">{{ $emple->codigo }} - {{ $emple->ocupacion }}</option>
                                @endforeach
                            </select>
                            @error('id_emple_ocupacion') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-teal-600">Asignar a Ocupación IGSS *</label>
                            <select name="id_igss_ocupacion" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="">Seleccione una opción</option>
                                @foreach($igssOcupaciones as $igss)
                                    <option value="{{ $igss->id }}">{{ Str::limit($igss->descripcion, 100) }}</option>
                                @endforeach
                            </select>
                            @error('id_igss_ocupacion') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('ocupacion.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Guardar Ocupación</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>