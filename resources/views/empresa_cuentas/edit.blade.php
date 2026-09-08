<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Editar Cuenta Bancaria
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            
            <form action="{{ route('empresa-cuentas.update', $empresaCuenta->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Banco *</label>
                    <select name="banco_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        @foreach($bancos as $banco)
                            <option value="{{ $banco->id }}" {{ $empresaCuenta->banco_id == $banco->id ? 'selected' : '' }}>
                                {{ $banco->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Número de Cuenta *</label>
                    <input type="text" name="numero_cuenta" value="{{ $empresaCuenta->numero_cuenta }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold text-sm text-gray-700 mb-2">Descripción (Uso)</label>
                    <input type="text" name="descripcion" value="{{ $empresaCuenta->descripcion }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="estado" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ $empresaCuenta->estado ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 font-bold">Cuenta Activa</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('empresa-cuentas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white py-2 px-6 rounded shadow font-bold">Actualizar Cuenta</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>