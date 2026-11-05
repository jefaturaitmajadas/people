<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-800 mb-1">Cambiar contraseña</h2>

    @if(Auth::user()->debe_cambiar_password)
        <p class="text-sm text-gray-600 mb-4">Por seguridad debes crear una contraseña nueva antes de continuar.</p>
    @else
        <p class="text-sm text-gray-600 mb-4">Escribe tu contraseña actual y la nueva.</p>
    @endif

    @if(session('success'))
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.cambiar.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <x-input-label for="current_password" value="Contraseña actual" />
            <x-text-input id="current_password" name="current_password" type="password" class="block mt-1 w-full" required autofocus autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="password" value="Contraseña nueva (mínimo 8 caracteres)" />
            <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="password_confirmation" value="Confirmar contraseña nueva" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" required autocomplete="new-password" />
        </div>

        <x-primary-button class="w-full justify-center">Guardar contraseña</x-primary-button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-gray-500 hover:text-gray-800 underline">Cerrar sesión</button>
    </form>
</x-guest-layout>
