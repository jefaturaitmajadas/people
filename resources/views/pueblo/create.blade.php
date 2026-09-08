<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Pueblo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('pueblo.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Pueblo (Sistema) *</label>
                        <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 font-medium" placeholder="Ej: Maya, Xinca, Garífuna, Mestizo" required>
                        @error('nombre') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Asignar a Pueblo (Catálogo Empleador) *</label>
                        <select name="id_empl_pueblo_per" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            <option value="">Seleccione el equivalente correspondiente</option>
                            @foreach($emplPueblos as $pueblo_empl)
                                <option value="{{ $pueblo_empl->id }}">Código {{ $pueblo_empl->codigo }} - {{ $pueblo_empl->pueblo_de_pertenencia }}</option>
                            @endforeach
                        </select>
                        @error('id_empl_pueblo_per') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-8">
                        <a href="{{ route('pueblo.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Guardar Pueblo</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>