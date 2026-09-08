<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Registrar Nueva Profesión
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('profesiones.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Nombre de la Profesión u Oficio *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" placeholder="Ej: Perito Contador, Licenciado en Administración..." required autofocus>
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('profesiones.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow font-bold transition-colors">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white py-2 px-6 rounded shadow font-bold transition-colors">Guardar Profesión</button>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>