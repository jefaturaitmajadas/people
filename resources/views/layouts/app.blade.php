<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100">
        
        <!-- Contenedor Principal Flex (AQUÍ AGREGAMOS EL x-data) -->
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden bg-gray-100">
            
            <!-- 1. El Sidebar Izquierdo (Móvil y Escritorio) -->
            @include('layouts.sidebar')

            <!-- 2. Área de Contenido Principal (Derecha) -->
            <div class="flex flex-col flex-1 w-0 overflow-hidden">
                
                <!-- Header Superior -->
                @include('layouts.navigation')

                <!-- Título de la Página -->
                @isset($header)
                    <header class="bg-white shadow z-10">
                        <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Contenido Dinámico -->
                <main class="flex-1 overflow-y-auto focus:outline-none">
                    <div class="py-6">
                        <div class="px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>