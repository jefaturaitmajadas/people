<!-- Fondo Oscuro para Móvil (Se cierra al hacer clic fuera del menú) -->
<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false" 
     class="fixed inset-0 z-40 bg-gray-900/80 md:hidden" 
     style="display: none;"></div>

<!-- Contenedor del Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 px-4 py-8 overflow-y-auto transition-transform duration-300 transform bg-gray-900 border-r border-gray-700 md:static md:translate-x-0">
    
    <!-- Header del Sidebar con Botón de Cerrar (Móvil) -->
    <div class="flex items-center justify-between mb-6 md:justify-center">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <span class="text-2xl font-black text-white uppercase">People <span class="text-indigo-500">RRHH</span></span>
        </a>

        <!-- Indicador de Empresa Activa -->
    @if(session('empresa_activa_id'))
        <div class="mt-4 p-3 bg-gray-800 rounded-lg border border-gray-700 text-center">
            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Entorno Activo</p>
            <p class="text-sm font-bold text-indigo-400 truncate" title="{{ session('empresa_activa_nombre') }}">
                {{ session('empresa_activa_nombre') }}
            </p>
            @unless(Auth::user()->isEmpleado())
            <a href="{{ route('dashboard') }}" class="inline-block mt-2 text-xs text-gray-300 hover:text-white bg-gray-700 px-3 py-1 rounded transition-colors">
                ⇄ Cambiar Empresa
            </a>
            @endunless
        </div>
    @endif
        <!-- Botón de Cerrar (Solo visible en Móvil) -->
        <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white focus:outline-none md:hidden">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navegación -->
    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav class="space-y-2">
            @if(Auth::user()->isEmpleado())
            <!-- El colaborador solo tiene acceso a su ficha -->
            <a href="{{ route('empleados.edit', Auth::user()->empleado_id) }}" class="flex items-center px-4 py-2 text-gray-100 bg-gray-800 rounded-lg hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="mx-4 font-medium">Mi Ficha</span>
            </a>
            @else
            
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-100 bg-gray-800 rounded-lg hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            <!-- Módulo: RRHH -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-400 transition-colors rounded-lg hover:bg-gray-800 hover:text-gray-100">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="mx-4 font-medium">Recursos Humanos</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="mt-2 space-y-1 bg-gray-800 rounded-lg p-2" x-collapse>
                    <a href="{{ route('empleados.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Colaboradores</a>
                </div>
            </div>

            <!-- Módulo: Planilla y Nómina -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-400 transition-colors rounded-lg hover:bg-gray-800 hover:text-gray-100">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="mx-4 font-medium">Nómina y Pagos</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="mt-2 space-y-1 bg-gray-800 rounded-lg p-2" x-collapse>
                    <a href="{{ route('planillas.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Gestión de Planillas</a>
                    <a href="{{ route('empresa-cuentas.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Cuentas Bancarias</a>
                    
                </div>
            </div>

            <!-- Módulo: Reportes -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-400 transition-colors rounded-lg hover:bg-gray-800 hover:text-gray-100">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="mx-4 font-medium">Reportes</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="mt-2 space-y-1 bg-gray-800 rounded-lg p-2" x-collapse>
                    <a href="{{ route('reportes.empleador') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Reporte del Empleador</a>
                </div>
                <div x-show="open" class="mt-2 space-y-1 bg-gray-800 rounded-lg p-2" x-collapse>
                    <a href="{{ route('reportes.vacaciones') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Reporte de Vacaciones</a>
                </div>
                <div x-show="open" class="mt-2 space-y-1 bg-gray-800 rounded-lg p-2" x-collapse>
                    <a href="{{ route('reportes.cumpleanos') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Cumpleañeros del Mes</a>
                    <a href="{{ route('reportes.cumpleanos.arte') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Arte Cumpleañeros</a>
                </div>
                
                

            </div>

            

            <!-- Módulo: CATALOGOS ADMIN -->
             @if((Auth::user()->rol === 'administrador')||(Auth::user()->rol === 'gerente'))
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-400 transition-colors rounded-lg hover:bg-gray-800 hover:text-gray-100">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="mx-4 font-medium">Administración</span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" class="mt-2 space-y-1 bg-gray-800 rounded-lg p-2" x-collapse>
                    <a href="{{ route('empresas.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Empresas</a>
                    <a href="{{ route('puesto.index') }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Puestos</a>
                    <a href="{{ route('banco.index') }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-white">Bancos</a>
                    <a href="{{ route('departamentos.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Departamentos</a>
                    <a href="{{ route('tipo_identificacions.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Identificación</a>
                    <a href="{{ route('actividad-economica.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Actividad Economica</a>
                    <a href="{{ route('pais.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Pais </a>

                    <a href="{{ route('igss-departamento.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Departamento </a>
                    <a href="{{ route('municipio.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Municipio </a>

                    <a href="{{ route('nacionalidad-pais.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Nacionalidad</a>
                    <a href="{{ route('discapacidad.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Discapacidad </a>
                    <a href="{{ route('ocupacion.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Ocupación</a>
                    <a href="{{ route('genero.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Género</a>
                    <a href="{{ route('estado-civil.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Estado Civil</a>
                    <a href="{{ route('turno.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Turno</a>
                    <a href="{{ route('tipo-contrato.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Contrato</a>
                    <a href="{{ route('nivel-educativo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Nivel Educativo</a>
                    <a href="{{ route('pueblo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Pueblo</a>
                    <a href="{{ route('comunidad-linguistica.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Linguistica</a>
                    <a href="{{ route('tipo-salario.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Salario</a>
                    <a href="{{ route('tipos-planilla.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Planilla</a>
                    <a href="{{ route('tipo-pago.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Pago</a>
                    <a href="{{ route('moneda.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Moneda</a>
                    <a href="{{ route('seguro.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Seguro</a>
                    <a href="{{ route('tipo-cuenta.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo Cuenta</a>
                    <a href="{{ route('centro-costo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Centro de Costo</a>
                    <a href="{{ route('mano-obra.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Mano de obra</a>
                    <a href="{{ route('titulo-academico.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Título Academico</a>
                    <a href="{{ route('grado-academico.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Grado Academico</a>
                    <a href="{{ route('tipo-documento.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo Documento</a>
                    <a href="{{ route('religion.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Religion</a>
                    <a href="{{ route('tipo-casa.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Casa</a>
                    <a href="{{ route('tipo-transporte.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Transporte</a>
                    <a href="{{ route('tipo-incapacidad.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Incapacidad</a>
                    <a href="{{ route('tipo-suspension.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Suspensión</a>
                    <a href="{{ route('tipo-vacaciones.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Vacaciones</a>
                    <a href="{{ route('tipo-permiso.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Permisos</a>


                     <a href="{{ route('profesiones.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Profesión</a>
                    <a href="{{ route('igss-paises.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Pais Igss</a>
                    <a href="{{ route('igss-ocupacion.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Ocupación Igss</a>
                    <a href="{{ route('igss-tipo-salario.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo Salario Igss</a>
                    

                    <a href="{{ route('empl-discapacidad.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Discapacidad R. Empleador</a>
                    <a href="{{ route('emple-ocupacion.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Ocupación R. Empleador</a>
                    <a href="{{ route('empl-sexo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Género R. Empleador</a>
                    <a href="{{ route('empl-estado-civil.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Estado Civil R. Empleador</a>
                    <a href="{{ route('empl-jornada-trabajo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Jornada Laboral R. Empleador</a>
                    <a href="{{ route('empl-doc-identificacion.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Doc. Identificación R. Empleador</a>
                    <a href="{{ route('empl-temp-contrato.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Temporalidad R. Empleador</a>
                    <a href="{{ route('empl-tipo-contrato.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipo de Contrato R. Empleador</a>
                    <a href="{{ route('empl-nivel-educativo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Nivel Educativo R. Empleador</a>
                    <a href="{{ route('empl-pueblo.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Pueblo R. Empleador</a>
                    <a href="{{ route('empl-linguistica.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Linguistica R. Empleador</a>
                    <a href="{{ route('tipo-descuentos.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipos de Descuento</a>
                    <a href="{{ route('tipo-ingresos.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipos de Ingreso</a>
                    <a href="{{ route('tipo-incidencias.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Tipos de Incidencia</a>
                    
                    
                </div>
            </div>
            @endif

            <!-- Administración de Usuarios (SOLO VISIBLE PARA ADMIN) -->
            @if(Auth::user()->rol === 'administrador')
            <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-400 transition-colors rounded-lg hover:bg-indigo-900 hover:text-white border border-transparent hover:border-indigo-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span class="mx-4 font-bold text-indigo-400">Gestión de Usuarios</span>
            </a>
            @endif
            @endif

        </nav>
    </div>
</aside>