<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Reporte General de Empleados
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">

        <div class="bg-white p-6 rounded-lg shadow mb-6 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $empresa->nombre }}</h3>
                <p class="text-sm text-gray-500">{{ $filas->count() }} {{ $filas->count() === 1 ? 'empleado' : 'empleados' }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('reportes.empleados') }}" class="flex flex-wrap items-center gap-2">
                    <input type="search" name="buscar" value="{{ $buscar }}" placeholder="Nombre, código o identificación" aria-label="Buscar colaborador" class="w-64 border-gray-300 rounded-md shadow-sm text-sm">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow transition-colors">Buscar</button>
                    @if ($buscar !== '')
                        <a href="{{ route('reportes.empleados', ['estado' => $estado]) }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Limpiar</a>
                    @endif

                    <label for="estado" class="text-sm font-bold text-gray-700">Estado</label>
                    <select id="estado" name="estado" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm text-sm">
                        @foreach ($estados as $valor => $nombre)
                            <option value="{{ $valor }}" @selected($estado === $valor)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('reportes.empleados.excel', ['estado' => $estado, 'buscar' => $buscar ?: null]) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    Exportar a Excel
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-auto" style="max-height: 70vh">
            <table class="min-w-full text-sm border-separate border-spacing-0">
                <thead>
                    <tr>
                        @foreach ($encabezados as $encabezado)
                            <th class="sticky top-0 {{ $loop->first ? 'left-0 z-20' : 'z-10' }} bg-gray-100 p-2 border-b border-r border-gray-300 text-left font-bold text-gray-700 whitespace-nowrap">{{ $encabezado }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($filas as $fila)
                        <tr>
                            @foreach ($fila as $valor)
                                <td class="{{ $loop->first ? 'sticky left-0 bg-white font-bold' : '' }} p-2 border-b border-r border-gray-200 whitespace-nowrap {{ is_float($valor) ? 'text-right' : '' }}">
                                    @if ($valor instanceof \DateTimeInterface)
                                        {{ $valor->format('d/m/Y') }}
                                    @elseif (is_float($valor))
                                        {{ number_format($valor, 2) }}
                                    @else
                                        {{ $valor }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($encabezados) }}" class="p-6 text-center text-gray-500 font-bold">
                                No se encontraron empleados con estos filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
