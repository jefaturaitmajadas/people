<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Editar Empresa: {{ $empresa->nombre }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">

            <!-- Mostrar errores de validación -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong class="font-bold">Hay errores en el formulario:</strong>
                    <ul class="list-disc pl-5 mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ⚠️ IMPORTANTE: enctype="multipart/form-data" es obligatorio para enviar archivos -->
            <form action="{{ route('empresas.update', $empresa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Nombre -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Nombre de la Empresa *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <!-- Razón Social -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Razón Social (Legal)</label>
                        <input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Colaboración de Personal, S.A.">
                    </div>

                    <!-- NIT -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">NIT *</label>
                        <input type="text" name="nit" value="{{ old('nit', $empresa->nit) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <!-- Número Patronal IGSS -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Número Patronal (IGSS)</label>
                        <input type="text" name="numero_patronal" value="{{ old('numero_patronal', $empresa->numero_patronal) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: 123456-7">
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Dirección Física</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Anillo Periférico 29-98 Zona 11, Guatemala">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: (502) 2200-9696">
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-2">Correo Electrónico (Para footer)</label>
                        <input type="email" name="correo" value="{{ old('correo', $empresa->correo) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: info@empresa.com">
                    </div>

                    <!-- Firmante RRHH -->
                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Nombre y Título del Firmante de RRHH</label>
                        <input type="text" name="firmante_rrhh" value="{{ old('firmante_rrhh', $empresa->firmante_rrhh) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Licda. Paola Santiago">
                        <p class="text-xs text-gray-500 mt-1">Este nombre aparecerá automáticamente en las firmas de constancias y cartas laborales.</p>
                    </div>
                </div>

                <!-- Sección de Logo -->
                <div class="mb-6 border p-4 rounded-lg bg-gray-50">
                    <h3 class="font-bold text-gray-700 mb-4">Logotipo de la Empresa</h3>
                    
                    <div class="flex items-center gap-6">
                        <!-- Mostrar el logo actual si existe -->
                        @if($empresa->logo)
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mb-2 uppercase font-semibold">Logo Actual</p>
                                <div class="bg-white border p-2 rounded shadow-sm inline-block">
                                    <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo {{ $empresa->nombre }}" class="h-24 object-contain">
                                </div>
                            </div>
                        @endif

                        <!-- Input para subir un logo nuevo -->
                        <div class="flex-1">
                            <label class="block font-bold text-sm text-gray-700 mb-2">
                                {{ $empresa->logo ? 'Cambiar Logo (Opcional)' : 'Subir Logo *' }}
                            </label>
                            <input type="file" name="logo" accept="image/png, image/jpeg, image/svg+xml" class="w-full border border-gray-300 bg-white rounded-md p-2 text-sm">
                            <p class="text-xs text-gray-500 mt-2">
                                Formatos permitidos: JPG, PNG, SVG. Tamaño máximo: 2MB.<br>
                                Se recomienda usar imágenes con fondo transparente (PNG/SVG) para el recibo.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t pt-4">
                    <a href="{{ route('empresas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded shadow">Cancelar</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white py-2 px-6 rounded shadow font-bold">
                        Actualizar Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>