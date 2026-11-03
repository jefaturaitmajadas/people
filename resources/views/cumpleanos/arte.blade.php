<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Arte de Cumpleañeros del Mes
        </h2>
    </x-slot>

    @php
        // Banderines "HAPPY BIRTHDAY" colgados sobre una curva
        $letras = str_split('HAPPYBIRTHDAY');
        $coloresBanderin = ['#E9A23B', '#E5737F', '#8E90D6'];
        $banderines = [];
        foreach ($letras as $i => $letra) {
            $t = ($i + 0.5) / count($letras);
            $banderines[] = [
                'letra' => $letra,
                'x' => 800 * $t,
                'y' => 10 + 200 * $t * (1 - $t),
                'angulo' => rad2deg(atan((200 - 400 * $t) / 800)),
                'color' => $coloresBanderin[$i % 3],
            ];
        }

        // Ajuste de tamaño de letra según la cantidad de cumpleañeros
        $total = $empleados->count();
        $tamanoLetra = $total <= 20 ? 21 : ($total <= 28 ? 17 : 14);
        $altoFila = $tamanoLetra + 13;
    @endphp

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Panel de Acciones -->
        <div class="bg-white p-6 rounded-lg shadow mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4">
            @include('cumpleanos._filtros', ['ruta' => 'reportes.cumpleanos.arte'])

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reportes.cumpleanos', ['mes' => $mesSeleccionado, 'anio' => $anioSeleccionado]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow transition-colors">
                    📋 Ver Reporte
                </a>
                <button type="button" id="btn-copiar" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    📋 Copiar Imagen
                </button>
                <button type="button" id="btn-descargar" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    ⬇️ Descargar PNG
                </button>
            </div>
        </div>

        <p class="text-sm text-gray-500 mb-4 text-center">
            Usa <strong>Copiar Imagen</strong> y pégala directamente en el cuerpo del correo general, o descárgala como PNG para adjuntarla.
            <span id="estado-arte" class="font-bold text-indigo-600"></span>
        </p>

        <!-- ARTE -->
        <div class="overflow-x-auto pb-6">
            <div id="arte-cumpleanos" class="arte">

                <!-- Banderines -->
                <svg class="banderines" viewBox="0 0 800 170" width="800" height="170" xmlns="http://www.w3.org/2000/svg">
                    <path d="M -10 8 Q 400 112 810 8" stroke="#4C5BB0" stroke-width="5" fill="none" />
                    @foreach($banderines as $b)
                        <g transform="translate({{ round($b['x'], 1) }} {{ round($b['y'], 1) }}) rotate({{ round($b['angulo'], 1) }})">
                            <path d="M -26 0 L 26 0 L 0 62 Z" fill="{{ $b['color'] }}" opacity="0.92" />
                            <text x="0" y="30" text-anchor="middle" font-family="Quicksand, Arial, sans-serif" font-size="24" font-weight="700" fill="#FFFFFF">{{ $b['letra'] }}</text>
                        </g>
                    @endforeach
                </svg>

                <!-- Título -->
                <div class="titulo">
                    <div class="titulo-1">Cumpleañeros</div>
                    <div class="titulo-2">{{ $meses[$mesSeleccionado] }}</div>
                </div>

                <!-- Pastel -->
                <svg class="pastel" viewBox="0 0 200 200" width="190" height="190" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="160" rx="85" ry="22" fill="#D9A07C" />
                    <rect x="15" y="95" width="170" height="65" fill="#E3B08E" />
                    <ellipse cx="100" cy="95" rx="85" ry="24" fill="#7F84D6" />
                    <path d="M15 95 q0 22 10 30 q8 -14 14 4 q6 18 12 -2 q8 -20 14 6 q6 16 14 -4 q8 -16 14 6 q8 20 14 -2 q6 -18 14 2 q8 18 14 -6 q6 -14 12 4 q10 -6 14 -38 Z" fill="#7F84D6" />
                    @foreach([60, 100, 140] as $vx)
                        <rect x="{{ $vx - 4 }}" y="45" width="8" height="45" rx="2" fill="#B7C3F2" />
                        <path d="M{{ $vx }} 26 q-8 10 0 18 q8 -8 0 -18 Z" fill="#F2A23B" />
                    @endforeach
                </svg>

                <!-- Gorros de fiesta -->
                <svg class="gorros" viewBox="0 0 220 220" width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                    <path d="M120 40 L195 190 Q160 205 110 195 Z" fill="#7FC9C8" />
                    @foreach([70, 100, 130, 160] as $gy)
                        <path d="M{{ 120 + ($gy - 40) * 0.25 }} {{ $gy }} L{{ 120 + ($gy - 40) * 0.5 }} {{ $gy + 8 }}" stroke="#E5737F" stroke-width="9" stroke-linecap="round" />
                    @endforeach
                    <circle cx="120" cy="34" r="16" fill="#4C5BB0" />
                    <path d="M70 50 L125 195 Q80 210 25 190 Z" fill="#E9B85A" />
                    @foreach([[60, 110], [80, 150], [50, 165], [95, 180], [70, 85]] as $p)
                        <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="7" fill="#9A8AC8" />
                    @endforeach
                    <circle cx="70" cy="44" r="16" fill="#4C5BB0" />
                </svg>

                <!-- Lista de Cumpleañeros -->
                <div class="panel">
                    @forelse($empleados as $empleado)
                        <div class="fila" style="font-size: {{ $tamanoLetra }}px; min-height: {{ $altoFila }}px;">
                            <span class="dia">{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d') }}</span>
                            <span class="nombre">{{ $empleado->nombre_corto }}</span>
                            <span class="guion">-</span>
                            <span class="depto">{{ $empleado->departamento->nombre ?? '' }}</span>
                        </div>
                    @empty
                        <div class="vacio">No hay cumpleañeros en {{ $meses[$mesSeleccionado] }} {{ $anioSeleccionado }}.</div>
                    @endforelse
                </div>

                <div class="felicidades">¡Felicidades!</div>

                <!-- Logo de la empresa -->
                <div class="logo">
                    @if($empresa->logo)
                        <img src="{{ asset('storage/' . $empresa->logo) }}" alt="{{ $empresa->nombre }}">
                    @else
                        <div class="logo-texto">{{ $empresa->nombre }}</div>
                    @endif
                </div>

                <!-- Globos -->
                @foreach(['globos-izq', 'globos-der'] as $lado)
                    <svg class="globos {{ $lado }}" viewBox="0 0 240 260" width="240" height="260" xmlns="http://www.w3.org/2000/svg">
                        @foreach([[55, 120, '#E9B85A'], [185, 125, '#E5737F'], [120, 90, '#7F84D6']] as $g)
                            <path d="M{{ $g[0] }} {{ $g[1] + 62 }} q -12 40 6 80" stroke="#8FB7D9" stroke-width="2" fill="none" />
                            <ellipse cx="{{ $g[0] }}" cy="{{ $g[1] }}" rx="50" ry="62" fill="{{ $g[2] }}" opacity="0.9" />
                            <path d="M{{ $g[0] - 6 }} {{ $g[1] + 60 }} l6 10 l6 -10 Z" fill="{{ $g[2] }}" />
                            <path d="M{{ $g[0] - 30 }} {{ $g[1] - 25 }} q 8 -25 30 -32" stroke="#FFFFFF" stroke-width="6" stroke-linecap="round" fill="none" opacity="0.8" />
                        @endforeach
                    </svg>
                @endforeach
            </div>
        </div>
    </div>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- crossorigin permite que la exportación lea e incruste las fuentes en la imagen -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet" crossorigin="anonymous">

    <style>
        .arte {
            position: relative;
            width: 800px;
            min-height: 1130px;
            margin: 0 auto;
            padding-bottom: 300px;
            overflow: hidden;
            font-family: 'Quicksand', Arial, sans-serif;
            background-color: #F8EBCB;
            background-image:
                radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.55) 0, transparent 30%),
                radial-gradient(circle at 85% 35%, rgba(240, 210, 150, 0.45) 0, transparent 28%),
                radial-gradient(circle at 30% 70%, rgba(255, 245, 220, 0.6) 0, transparent 35%),
                radial-gradient(circle at 80% 85%, rgba(235, 205, 140, 0.4) 0, transparent 30%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .arte .banderines { position: absolute; top: 0; left: 0; }
        .arte .titulo { position: relative; padding-top: 150px; text-align: center; color: #4C5BB0; font-family: 'Dancing Script', cursive; line-height: 1; z-index: 2; }
        .arte .titulo-1 { font-size: 78px; font-weight: 700; }
        .arte .titulo-2 { font-size: 88px; font-weight: 700; margin-top: -4px; }
        .arte .pastel { position: absolute; top: 215px; left: 25px; z-index: 3; }
        .arte .gorros { position: absolute; top: 230px; right: 15px; z-index: 3; }
        .arte .panel {
            position: relative;
            z-index: 2;
            margin: 70px 105px 0;
            padding: 45px 30px 30px 45px;
            background: #FFFFFF;
            border: 4px dotted #7F84D6;
            color: #2E2E3A;
        }
        .arte .fila { display: flex; align-items: baseline; font-weight: 500; line-height: 1.35; }
        .arte .dia { flex: 0 0 2.6em; font-weight: 600; }
        .arte .nombre { flex: 0 0 50%; }
        .arte .guion { flex: 0 0 1.6em; text-align: center; }
        .arte .depto { flex: 1; }
        .arte .vacio { text-align: center; font-size: 22px; padding: 40px 0; color: #6B6B80; }
        .arte .felicidades { position: relative; z-index: 4; text-align: center; font-family: 'Dancing Script', cursive; font-weight: 700; font-size: 70px; color: #4C5BB0; margin-top: 15px; }
        .arte .logo { position: relative; z-index: 4; text-align: center; margin-top: 10px; }
        .arte .logo img { max-height: 120px; max-width: 220px; margin: 0 auto; display: inline-block; }
        .arte .logo-texto { display: inline-block; background: #000; color: #FFF; font-weight: 700; font-size: 22px; padding: 18px 26px; max-width: 320px; white-space: normal; line-height: 1.2; }
        .arte .globos { position: absolute; bottom: -10px; z-index: 1; }
        .arte .globos-izq { left: 0; }
        .arte .globos-der { right: 0; transform: scaleX(-1); }
    </style>

    <!-- html-to-image usa el render nativo del navegador (html2canvas cortaba el texto y omitía los SVG) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>
    <script>
        (function () {
            const estado = document.getElementById('estado-arte');
            const nombreArchivo = 'Cumpleaneros_{{ $meses[$mesSeleccionado] }}_{{ $anioSeleccionado }}.png';
            // Pixel transparente por si el logo no carga, para que no falle la exportación
            const pixelVacio = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

            async function generarImagen() {
                await document.fonts.ready;
                const arte = document.getElementById('arte-cumpleanos');
                return htmlToImage.toBlob(arte, {
                    pixelRatio: 2,
                    imagePlaceholder: pixelVacio,
                    width: arte.offsetWidth,
                    height: arte.offsetHeight,
                    // El arte está centrado con margin auto; sin esto el margen calculado lo desplaza dentro de la imagen
                    style: { margin: '0', boxShadow: 'none' },
                });
            }

            document.getElementById('btn-descargar').addEventListener('click', async function () {
                estado.textContent = 'Generando imagen...';
                try {
                    const blob = await generarImagen();
                    const enlace = document.createElement('a');
                    enlace.download = nombreArchivo;
                    enlace.href = URL.createObjectURL(blob);
                    enlace.click();
                    setTimeout(() => URL.revokeObjectURL(enlace.href), 1000);
                    estado.textContent = 'Imagen descargada.';
                } catch (e) {
                    estado.textContent = 'No se pudo generar la imagen.';
                }
            });

            document.getElementById('btn-copiar').addEventListener('click', async function () {
                estado.textContent = 'Generando imagen...';
                try {
                    // Se pasa la promesa directamente para que Safari conserve el permiso del clic
                    await navigator.clipboard.write([new ClipboardItem({ 'image/png': generarImagen() })]);
                    estado.textContent = 'Imagen copiada. Pégala en tu correo (Ctrl/Cmd + V).';
                } catch (e) {
                    estado.textContent = 'Tu navegador no permite copiar imágenes; usa Descargar PNG.';
                }
            });
        })();
    </script>
</x-app-layout>
