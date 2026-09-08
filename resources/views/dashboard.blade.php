<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Selecciona el Entorno de Trabajo
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 text-center">
            <p class="text-gray-600">Hola, <span class="font-bold">{{ Auth::user()->name }}</span>. Tienes rol de <span class="uppercase font-bold text-indigo-600">{{ Auth::user()->rol }}</span>.</p>
            <p class="text-sm text-gray-500">Haz clic en una empresa para cargar sus empleados y nóminas.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($empresas as $empresa)
                <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow p-6 text-center border-t-4 border-indigo-500">
                    @if($empresa->logo)
                        <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo" class="h-20 mx-auto mb-4 object-contain">
                    @else
                        <div class="h-20 w-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 font-bold text-2xl">
                            {{ substr($empresa->nombre, 0, 1) }}
                        </div>
                    @endif
                    
                    <h3 class="text-lg font-bold text-gray-800">{{ $empresa->nombre }}</h3>
                    <p class="text-sm text-gray-500 mb-4">NIT: {{ $empresa->nit }}</p>

                    <form action="{{ route('entorno.activar', $empresa->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded transition-colors">
                            Ingresar al Entorno
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-3 text-center p-12 bg-white rounded shadow">
                    <p class="text-gray-500">No tienes ninguna empresa asignada. Contacta al Administrador.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>