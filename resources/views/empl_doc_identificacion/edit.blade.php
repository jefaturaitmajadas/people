<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Documento (Empleador)</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('empl-doc-identificacion.update', $doc_identificacion->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Código *</label>
                        <input type="number" name="codigo" value="{{ $doc_identificacion->codigo }}" class="shadow border rounded w-full py-2 px-3" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Documento de Identificación *</label>
                        <input type="text" name="documento_identificacion" value="{{ $doc_identificacion->documento_identificacion }}" class="shadow border rounded w-full py-2 px-3" required>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <a href="{{ route('empl-doc-identificacion.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>