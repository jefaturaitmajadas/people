<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal de Talento Humano</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts (Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-indigo-500 selection:text-white">

    <!-- Navegación Superior -->
    <nav class="absolute top-0 w-full px-6 py-4 flex justify-between items-center z-10">
        <div class="flex items-center gap-2">
            <!-- Ícono del Logo -->
            <div class="bg-indigo-600 p-2 rounded-lg text-white shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <span class="font-black text-xl tracking-tight text-gray-800">HR<span class="text-indigo-600">Portal</span></span>
        </div>

        <div>
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Ir al Panel Principal</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Iniciar Sesión</a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Sección Principal (Hero) -->
    <main class="relative flex flex-col items-center justify-center min-h-screen overflow-hidden">
        
        <!-- Elementos decorativos de fondo (Burbujas suaves) -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto mt-16">
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 leading-tight mb-6 tracking-tight">
                Gestión de talento humano, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-sky-500">hecha para personas.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Centraliza el control de expedientes, planillas y vacaciones en una sola plataforma. Simple, rápida y segura.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                        Entrar al Sistema
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>

        <!-- Tarjetas de Módulos (Características) -->
        <!-- Cambiamos a md:grid-cols-2 y reducimos el max-w para que se vean centradas -->
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 mt-20 px-6 max-w-4xl mx-auto w-full">
            
            <!-- Tarjeta 1 -->
            <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center text-indigo-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Planillas Exactas</h3>
                <p class="text-gray-600 text-sm">Cálculo automatizado de salarios, horas extras, descuentos de IGSS e integración contable.</p>
            </div>

            <!-- Tarjeta 2 -->
            <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="bg-sky-100 w-12 h-12 rounded-lg flex items-center justify-center text-sky-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Control de Descansos</h3>
                <p class="text-gray-600 text-sm">Gestión de saldos de vacaciones con reglas especiales por empresa y registro de historial.</p>
            </div>

        </div>
    </main>

    <footer class="w-full text-center py-6 text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} HR Portal. Todos los derechos reservados.</p>
    </footer>

</body>
</html>