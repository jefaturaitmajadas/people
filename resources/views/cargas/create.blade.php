<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cargar Nueva Planilla') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('cargas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4 col-span-2 md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Carga</label>
                            <input type="date" name="fecha_carga" value="{{ date('Y-m-d') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        </div>

                        <div class="mb-4 col-span-2 md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Mes de Planilla</label>
                            <select name="mes_planilla" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="Enero">Enero</option>
                                <option value="Febrero">Febrero</option>
                                <option value="Marzo">Marzo</option>
                                <option value="Abril">Abril</option>
                                <option value="Mayo">Mayo</option>
                                <option value="Junio">Junio</option>
                                <option value="Julio">Julio</option>
                                <option value="Agosto">Agosto</option>
                                <option value="Septiembre">Septiembre</option>
                                <option value="Octubre">Octubre</option>
                                <option value="Noviembre">Noviembre</option>
                                <option value="Diciembre">Diciembre</option>
                            </select>
                        </div>

                        <div class="mb-4 col-span-2 md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Año de Planilla</label>
                            <input type="number" name="anio_planilla" value="{{ date('Y') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        </div>

                        <div class="mb-4 col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Archivo Excel (.xlsx, .csv)</label>
                            <input type="file" name="archivo_excel" accept=".xlsx, .xls, .csv" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                            <p class="text-xs text-gray-500 mt-1">Asegúrate de que el archivo contenga la columna "codigo_empleado".</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <a href="{{ route('cargas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">Cancelar</a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">Procesar Excel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>