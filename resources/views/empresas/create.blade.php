<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Empresa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Empresa</label>
                        <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Razón social de la Empresa</label>
                        <input type="text" name="razon_social" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIT</label>
                        <input type="text" name="nit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    </div>

                    <!-- Número Patronal IGSS -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Número Patronal (IGSS)</label>
                        <input type="text" name="numero_patronal"  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: 123456-7">
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Dirección Física</label>
                        <input type="text" name="direccion"  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Anillo Periférico 29-98 Zona 11, Guatemala">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Teléfono</label>
                        <input type="text" name="telefono"  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: (502) 2200-9696">
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Correo Electrónico (Para footer)</label>
                        <input type="email" name="correo"  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: info@empresa.com">
                    </div>

                    <!-- Firmante RRHH -->
                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Nombre y Título del Firmante de RRHH</label>
                        <input type="text" name="firmante_rrhh"  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Licda. Paola Santiago">
                        <p class="text-xs text-gray-500 mt-1">Este nombre aparecerá automáticamente en las firmas de constancias y cartas laborales.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Logo de la Empresa</label>
                        <input type="file" name="logo" accept="image/*" class="w-full border rounded p-2">
                        <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG, SVG (Máx. 2MB)</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('empresas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>