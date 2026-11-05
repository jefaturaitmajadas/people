<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Nuevo Usuario
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">

            @if($errors->any())
                <div class="mb-6 p-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Datos Básicos -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <!-- Rol -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Rol del Usuario</label>
                        <select name="rol" id="rol-select" class="w-full border-gray-300 rounded-md" required>
                            <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador (Acceso Total)</option>
                            <option value="gerente" {{ old('rol') == 'gerente' ? 'selected' : '' }}>Gerente (Varias Empresas)</option>
                            <option value="generalista" {{ old('rol', 'generalista') == 'generalista' ? 'selected' : '' }}>Generalista de RRHH</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Contraseña</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded-md" minlength="8" placeholder="Mínimo 8 caracteres" required>
                    </div>
                </div>

                <!-- Asignación de Empresas -->
                <div id="empresas-container" class="mb-6 border p-4 rounded-lg bg-gray-50 {{ old('rol') == 'administrador' ? 'hidden' : '' }}">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Empresas Asignadas (Entornos permitidos)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($empresas as $empresa)
                            <label class="inline-flex items-center bg-white p-3 border rounded shadow-sm cursor-pointer hover:bg-gray-100">
                                <input type="checkbox" name="empresas[]" value="{{ $empresa->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5"
                                    {{ in_array($empresa->id, old('empresas', [])) ? 'checked' : '' }}>
                                <span class="ml-3 text-gray-700 font-medium">{{ $empresa->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Si seleccionas Administrador, tendrá acceso a todas por defecto y esta sección se ignorará.</p>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white py-2 px-6 rounded shadow font-bold">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script simple para ocultar las empresas si se elige Administrador -->
    <script>
        document.getElementById('rol-select').addEventListener('change', function() {
            var empresasDiv = document.getElementById('empresas-container');
            if (this.value === 'administrador') {
                empresasDiv.classList.add('hidden');
            } else {
                empresasDiv.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>