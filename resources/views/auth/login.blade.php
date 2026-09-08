<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - HR Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts (Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-indigo-500 selection:text-white">
    
    <div class="relative flex flex-col items-center justify-center min-h-screen overflow-hidden">
        
        <!-- Elementos decorativos de fondo -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-96 h-96 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="relative z-10 w-full max-w-md px-6">
            
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center gap-2 transition-transform transform hover:scale-105">
                    <div class="bg-indigo-600 p-2 rounded-lg text-white shadow-md">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="font-black text-3xl tracking-tight text-gray-800">HR<span class="text-indigo-600">Portal</span></span>
                </a>
            </div>

            <!-- Tarjeta de Login -->
            <div class="bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-1 text-center">¡Bienvenido de nuevo!</h2>
                <p class="text-gray-500 text-sm text-center mb-8">Ingresa tus credenciales para acceder al sistema.</p>

                <!-- Mensaje de estado (ej. cuando se restablece la contraseña) -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Correo Electrónico -->
                    <div class="mb-5">
                        <label for="email" class="block font-bold text-sm text-gray-700 mb-2">Correo Electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="ejemplo@empresa.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block font-bold text-sm text-gray-700">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recordarme -->
                    <div class="block mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600 font-medium hover:text-gray-900 transition-colors">Recordarme en este equipo</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all transform hover:-translate-y-0.5">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
            
            <p class="text-center text-gray-400 text-xs mt-8">&copy; {{ date('Y') }} HR Portal. Sistema de Talento Humano.</p>
        </div>
    </div>
</body>
</html>