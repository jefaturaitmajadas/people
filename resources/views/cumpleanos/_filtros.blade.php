<!-- Filtros de Mes y Año (No se imprimen) -->
<form action="{{ route($ruta) }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4">
    <div class="flex items-center gap-2">
        <label for="mes" class="font-bold text-gray-700">Mes:</label>
        <select name="mes" id="mes" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach($meses as $numero => $nombre)
                <option value="{{ $numero }}" {{ $mesSeleccionado == $numero ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-2">
        <label for="anio" class="font-bold text-gray-700">Año:</label>
        <select name="anio" id="anio" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach($aniosDisponibles as $anio)
                <option value="{{ $anio }}" {{ $anioSeleccionado == $anio ? 'selected' : '' }}>{{ $anio }}</option>
            @endforeach
        </select>
    </div>
</form>
