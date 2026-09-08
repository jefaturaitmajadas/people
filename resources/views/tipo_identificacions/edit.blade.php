<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Tipo de Identificación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tipo_identificacions.update', $tipoIdentificacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nombre del Documento (Sistema) *</label>
                    <input type="text" name="nombre" value="{{ $tipoIdentificacion->nombre }}" class="shadow border rounded w-full py-2 px-3" required>
                </div>

                <div class="mb-4 border-t pt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 text-indigo-600">Asignar a Documento (Empleador) *</label>
                    <select name="id_empl_doc_ident" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                        @foreach($emplDocs as $doc)
                            <option value="{{ $doc->id }}" {{ $tipoIdentificacion->id_empl_doc_ident == $doc->id ? 'selected' : '' }}>
                                Código {{ $doc->codigo }} - {{ $doc->documento_identificacion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('tipo_identificacions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm shadow">Actualizar Tipo</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>