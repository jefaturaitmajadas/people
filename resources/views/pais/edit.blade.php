<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar País del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('pais.update', $pais->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del País *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $pais->nombre) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4 border-t pt-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">País Empleador (Nacionalidad) *</label>
                            <select name="id_empl_nacionalidad" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @foreach($nacionalidades as $nac)
                                    <option value="{{ $nac->id }}" {{ $pais->id_empl_nacionalidad == $nac->id ? 'selected' : '' }}>
                                        {{ $nac->pais }} - {{ $nac->nacionalidad }} ({{ $nac->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_empl_nacionalidad') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-teal-600">País IGSS *</label>
                            <select name="id_igss_pais" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @foreach($igssPaises as $igss)
                                    <option value="{{ $igss->id }}" {{ $pais->id_igss_pais == $igss->id ? 'selected' : '' }}>
                                        {{ $igss->campo }} ({{ $igss->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_igss_pais') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('pais.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar País</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>