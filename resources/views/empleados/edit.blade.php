<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $soloLectura ? 'Mi Ficha' : 'Editar Empleado' }}: {{ $empleado->primer_nombre }} {{ $empleado->primer_apellido }}</h2>
    </x-slot>
    
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        @unless($soloLectura)
        <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Gestión de Nómina y Planilla</h3>
    
    <div class="flex flex-wrap gap-3">
        
        <!-- Botón Ingresos -->
        <a href="{{ route('ingresos.index', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-green-300 rounded-md font-bold text-green-700 hover:bg-green-50 shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Ingresos Extras
        </a>

        <!-- Botón Descuentos -->
        <a href="{{ route('descuentos.index', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-md font-bold text-red-700 hover:bg-red-50 shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            Descuentos
        </a>

        <!-- Botón Incidencias -->
        <a href="{{ route('incidencias.index', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-amber-300 rounded-md font-bold text-amber-700 hover:bg-amber-50 shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Incidencias / Faltas
        </a>

        <!-- Botón Constancia -->
        <a href="{{ route('empleados.constancia', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-blue-300 rounded-md font-bold text-blue-700 hover:bg-blue-50 shadow-sm transition-colors" target="_blank">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Constancia de Ingresos
        </a>

        <!-- Botón Constancia Laboral -->
        <a href="{{ route('empleados.constancia_laboral', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-purple-300 rounded-md font-bold text-purple-700 hover:bg-purple-50 shadow-sm transition-colors" target="_blank">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Constancia Laboral
        </a>

        <a href="{{ route('empleados.constancia_patronal', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-teal-300 rounded-md font-bold text-teal-700 hover:bg-teal-50 shadow-sm transition-colors" target="_blank">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Constancia Patronal(IGSS)
        </a>

        <!-- Botón Cálculo de Indemnización -->
        <a href="{{ route('empleados.proyeccion_indemnizacion', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-bold text-white hover:bg-black shadow-sm transition-colors" target="_blank">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            Calcular Indemnización
        </a>

        <!-- Botón Vacaciones -->
        <a href="{{ route('empleados.vacaciones.index', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-sky-300 rounded-md font-bold text-sky-700 hover:bg-sky-50 shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Control de Vacaciones
        </a>

         <a href="{{ route('empleados.prestamos.index', $empleado->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-sky-300 rounded-md font-bold text-sky-700 hover:bg-sky-50 shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Anticipo de Salario
        </a>


        <!-- Aquí podrás agregar luego: Horas Extras, Vacaciones, etc. -->
    </div>

    <!-- Acceso del colaborador al sistema -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        @if(session('success'))
            <p class="mb-2 text-sm font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="mb-2 text-sm font-medium text-red-700">{{ session('error') }}</p>
        @endif
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm text-gray-600">
                <span class="font-bold">Acceso del colaborador:</span>
                {{ $empleado->usuario ? 'usuario ' . $empleado->usuario->usuario : 'sin usuario' }}
            </span>
            <form action="{{ route('empleados.restablecer_acceso', $empleado->id) }}" method="POST" onsubmit="return confirm('La clave volverá a ser la identificación del colaborador. ¿Continuar?');">
                @csrf
                <button type="submit" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md font-bold text-gray-700 hover:bg-gray-100">
                    {{ $empleado->usuario ? 'Restablecer contraseña' : 'Crear acceso' }}
                </button>
            </form>
        </div>
    </div>
</div>
        @endunless

            <!-- NOTA: enctype="multipart/form-data" es obligatorio para subir fotos -->
            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT')

                <!-- El colaborador ve su ficha sin poder modificarla -->
                <fieldset @disabled($soloLectura)>
                
                <!-- FOTO DE PERFIL -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Fotografía</h3>
                <div class="flex items-center gap-6 mb-6">
                    <div class="w-24 h-24 bg-gray-200 rounded-full overflow-hidden flex items-center justify-center border">
                        @if($empleado->foto_perfil)
                            <img src="{{ asset('storage/' . $empleado->foto_perfil) }}" alt="Foto de perfil" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-500 text-sm">Sin foto</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Actualizar Foto de Perfil</label>
                        <input type="file" name="foto_perfil" accept="image/*" class="border rounded p-1 w-full text-sm">
                        <p class="text-xs text-gray-500 mt-1">Sube una nueva imagen si deseas reemplazar la actual.</p>
                    </div>
                </div>

                <!-- INFORMACIÓN CORPORATIVA -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Información Corporativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="text-sm font-bold">Empresa</label>
                        <select name="empresa_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($empresas as $t)
                                <option value="{{ $t->id }}" {{ $empleado->empresa_id == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold">Jefe</label>
                        <select name="jefe_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($jefes as $t)
                                <!-- Evitar que el empleado sea su propio jefe -->
                                @if($t->id != $empleado->id)
                                    <option value="{{ $t->id }}" {{ $empleado->jefe_id == $t->id ? 'selected' : '' }}>
                                        {{ $t->primer_nombre .' '.$t->primer_apellido }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                     <div><label class="text-sm font-bold">Unidad de Negocio (Depto)</label>
                        <select name="departamento_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($departamentos as $d)
                                <option value="{{ $d->id }}" {{ $empleado->departamento_id == $d->id ? 'selected' : '' }}>{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- INFORMACIÓN BASE -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Información Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold text-gray-700">Primer Nombre *</label><input type="text" name="primer_nombre" value="{{ old('primer_nombre', $empleado->primer_nombre) }}" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold text-gray-700">Segundo Nombre </label><input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $empleado->segundo_nombre) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Primer Apellido *</label><input type="text" name="primer_apellido" value="{{ old('primer_apellido', $empleado->primer_apellido) }}" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold text-gray-700">Segundo Apellido </label><input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $empleado->segundo_apellido) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Apellido Casada</label><input type="text" name="casada_apellido" value="{{ old('casada_apellido', $empleado->casada_apellido) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Télefono</label><input type="text" name="telefono" value="{{ old('telefono', $empleado->telefono) }}" class="w-full border rounded p-2" ></div>
                    <div><label class="text-sm font-bold text-gray-700">Télefono Casa</label><input type="text" name="telefono_casa" value="{{ old('telefono_casa', $empleado->telefono_casa) }}" class="w-full border rounded p-2" ></div>

                    <div>
                        <label class="text-sm font-bold">Tipo de Identificación</label>
                        <select name="identificacion_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($tipo_identificaciones as $t)
                                <option value="{{ $t->id }}" {{ $empleado->identificacion_id == $t->id ? 'selected' : '' }}>{{ $t->documento_identificacion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold text-gray-700">No. de Identificación</label><input type="text" name="identificacion" value="{{ old('identificacion', $empleado->identificacion) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Correo Corporativo</label><input type="email" name="correo_corporativo" value="{{ old('correo_corporativo', $empleado->correo_corporativo) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Correo Personal</label><input type="email" name="correo_personal" value="{{ old('correo_personal', $empleado->correo_personal) }}" class="w-full border rounded p-2"></div>
                </div>

                <!-- GESTIÓN -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Gestión y Contrato</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">Codigo Empleado</label><input type="text" name="codigo_empleado" value="{{ old('codigo_empleado', $empleado->codigo_empleado) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Fecha Inicio *</label><input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $empleado->fecha_inicio) }}" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Fecha Salida</label><input type="date" name="fecha_salida" value="{{ old('fecha_salida', $empleado->fecha_salida) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Tipo Contrato</label>
                        <select name="tipo_contrato_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($tipos_contrato as $t)
                                <option value="{{ $t->id }}" {{ $empleado->tipo_contrato_id == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Turno</label>
                        <select name="turno_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($turnos as $t)
                                <option value="{{ $t->id }}" {{ $empleado->turno_id == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="flex gap-4 col-span-4 mt-2">
                        <label><input type="checkbox" name="genera_planilla" {{ $empleado->genera_planilla ? 'checked' : '' }}> Genera Planilla</label>
                        <label><input type="checkbox" name="contratable" {{ $empleado->contratable ? 'checked' : '' }}> Contratable</label>
                    </div>
                </div>

                <!-- PERSONAL -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Datos Personales</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">NIT</label><input type="text" name="nit" value="{{ old('nit', $empleado->nit) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">No. IGSS</label><input type="text" name="no_igss" value="{{ old('no_igss', $empleado->no_igss) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">IRTRA</label><input type="text" name="irtra" value="{{ old('irtra', $empleado->irtra) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Género</label>
                        <select name="genero_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($generos as $g)
                                <option value="{{ $g->id }}" {{ $empleado->genero_id == $g->id ? 'selected' : '' }}>{{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Estado Civil</label>
                        <select name="estado_civil_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($estados_civiles as $e)
                                <option value="{{ $e->id }}" {{ $empleado->estado_civil_id == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div><label class="text-sm font-bold">Religiones</label>
                        <select name="religion_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($religiones as $e)
                                <option value="{{ $e->id }}" {{ $empleado->religion_id == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Fecha Nacimiento *</label><input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $empleado->fecha_nacimiento) }}" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">País Nacimiento</label>
                        <select name="pais_nacimiento_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($paises as $p)
                                <option value="{{ $p->id }}" {{ $empleado->pais_nacimiento_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- NOTA: Ajusté los name para que coincidan con la DB -->
                    <div><label class="text-sm font-bold">Municipio Nacimiento</label>
                        <select name="municipio_nacimiento_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($municipios as $p)
                                <option value="{{ $p->id }}" {{ $empleado->municipio_nacimiento_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Pueblo</label>
                        <select name="pueblo_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($pueblos as $p)
                                <option value="{{ $p->id }}" {{ $empleado->pueblo_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Comunidad Linguistica</label>
                        <select name="comunidad_linguistica_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($comunidades as $p)
                                <option value="{{ $p->id }}" {{ $empleado->comunidad_linguistica_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Nivel Educativo</label>
                        <select name="nivel_educativo_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($niveles_educativos as $p)
                                <option value="{{ $p->id }}" {{ $empleado->nivel_educativo_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div><label class="text-sm font-bold">Profesión</label>
                        <select name="profesion_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($profesiones as $p)
                                <option value="{{ $p->id }}" {{ $empleado->profesion_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div><label class="text-sm font-bold">Contacto Emergencia</label><input type="text" name="contacto_emergencia" value="{{ old('contacto_emergencia', $empleado->contacto_emergencia) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono Emergencia</label><input type="text" name="telefono_contacto_emergencia" value="{{ old('telefono_contacto_emergencia', $empleado->telefono_contacto_emergencia) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">País Residencia</label>
                        <select name="pais_residencia_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($paises as $p)
                                <option value="{{ $p->id }}" {{ $empleado->pais_residencia_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-4"><label class="text-sm font-bold">Dirección Exacta</label><textarea name="direccion_exacta" rows="2" class="w-full border rounded p-2">{{ old('direccion_exacta', $empleado->direccion_exacta) }}</textarea></div>

                    <div><label class="text-sm font-bold">Discapacidad</label>
                        <select name="discapacidad_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($discapacidades as $p)
                                <option value="{{ $p->id }}" {{ $empleado->discapacidad_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Ocupación</label>
                        <select name="ocupacion_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($ocupaciones as $p)
                                <option value="{{ $p->id }}" {{ $empleado->ocupacion_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-4 col-span-4 mt-2">
                        <label><input type="checkbox" name="aplica_credito_fiscal_isr" {{ $empleado->aplica_credito_fiscal_isr ? 'checked' : '' }}> Genera Crédito Fiscal ISR</label>
                    </div>

                    <div><label class="text-sm font-bold">Vive Con</label><input type="text" name="vive_con" value="{{ old('vive_con', $empleado->vive_con) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Tipo de Vivienda</label>
                        <select name="tipo_casa_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($tipos_casa as $p)
                                <option value="{{ $p->id }}" {{ $empleado->tipo_casa_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Tipo de Transporte</label>
                        <select name="tipo_transporte_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($tipos_transporte as $p)
                                <option value="{{ $p->id }}" {{ $empleado->tipo_transporte_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Cambiaste el campo boolean 'vehiculo' por texto para Placa en tu vista. Lo dejo así -->
                    <div><label class="text-sm font-bold">Placa</label><input type="text" name="vehiculo" value="{{ old('vehiculo', $empleado->vehiculo) }}" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Cantidad Hijos</label><input type="number" name="cantidad_hijos" value="{{ old('cantidad_hijos', $empleado->cantidad_hijos) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Cantidad Dependientes</label><input type="number" name="cantidad_dependientes" value="{{ old('cantidad_dependientes', $empleado->cantidad_dependientes) }}" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Nombre del padre</label><input type="text" name="nombre_padre" value="{{ old('nombre_padre', $empleado->nombre_padre) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono del padre</label><input type="text" name="telefono_padre" value="{{ old('telefono_padre', $empleado->telefono_padre) }}" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Nombre de la madre</label><input type="text" name="nombre_madre" value="{{ old('nombre_madre', $empleado->nombre_madre) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono de la madre</label><input type="text" name="telefono_madre" value="{{ old('telefono_madre', $empleado->telefono_madre) }}" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Nombre de Conyuge</label><input type="text" name="nombre_conyuge" value="{{ old('nombre_conyuge', $empleado->nombre_conyuge) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono de Conyuge</label><input type="text" name="telefono_conyuge" value="{{ old('telefono_conyuge', $empleado->telefono_conyuge) }}" class="w-full border rounded p-2"></div>
                </div>

                <!-- NÓMINA Y FINANZAS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Nómina y Finanzas</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">Puesto</label>
                        <select name="puesto_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($puestos as $p)
                                <option value="{{ $p->id }}" {{ $empleado->puesto_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Centro de Costo</label>
                        <select name="centro_costo_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($centros_costo as $c)
                                <option value="{{ $c->id }}" {{ $empleado->centro_costo_id == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Tipo Salario</label>
                        <select name="tipo_salario_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($tipos_salario as $b)
                                <option value="{{ $b->id }}" {{ $empleado->tipo_salario_id == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Moneda</label>
                        <select name="moneda_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($monedas as $p)
                                <option value="{{ $p->id }}" {{ $empleado->moneda_id == $p->id ? 'selected' : '' }}>{{ $p->simbolo.' '.$p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Salario Base</label><input type="number" step="0.01" name="salario" value="{{ old('salario', $empleado->salario) }}" class="w-full border rounded p-2"></div>
                    <div class="flex items-center gap-2 mt-4">
                        <input type="checkbox" name="salario_fijo" {{ $empleado->salario_fijo ? 'checked' : '' }}> 
                        <label>Salario fijoß</label>
                    </div>

                    <div><label class="text-sm font-bold">Salario Por Hora</label><input type="number" step="0.01" name="salario_por_hora" value="{{ old('salario_por_hora', $empleado->salario_por_hora) }}" class="w-full border rounded p-2"></div>
                    
                    <div><label class="text-sm font-bold">Banco</label>
                        <select name="banco_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($bancos as $b)
                                <option value="{{ $b->id }}" {{ $empleado->banco_id == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Tipo Cuenta</label>
                        <select name="tipo_cuenta_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($tipos_cuenta as $b)
                                <option value="{{ $b->id }}" {{ $empleado->tipo_cuenta_id == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Número de Cuenta</label><input type="text" name="numero_cuenta" value="{{ old('numero_cuenta', $empleado->numero_cuenta) }}" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Poliza</label><input type="text" name="poliza" value="{{ old('poliza', $empleado->poliza) }}" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Tipo Planilla</label>
                        <select name="igss_tipo_planilla_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($planillas as $b)
                                <option value="{{ $b->id }}" {{ $empleado->igss_tipo_planilla_id == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Mano de Obra</label>
                        <select name="mano_obra_id" class="w-full border rounded p-2">
                            <option value="">Seleccione</option>
                            @foreach($manos_obra as $b)
                                <option value="{{ $b->id }}" {{ $empleado->mano_obra_id == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    
                    
                    <div class="flex items-center gap-2 mt-4">
                        <input type="checkbox" name="marca_reloj" {{ $empleado->marca_reloj ? 'checked' : '' }}> 
                        <label>Marca Reloj</label>
                    </div>

                    <!-- Días Adicionales de Vacaciones -->
                    <div class="mb-4">
                        <label class="block font-bold text-sm text-gray-700 mb-2">Días Adicionales de Vacaciones (Bono)</label>
                        <input type="number" step="0.5" min="0" name="dias_adicionales_vacaciones" value="{{ old('dias_adicionales_vacaciones', $empleado->dias_adicionales_vacaciones ?? 0) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: 5">
                        <p class="text-xs text-gray-500 mt-1">Días extra que se sumarán a su saldo de ley. Si no aplica, déjalo en 0.</p>
                        @error('dias_adicionales_vacaciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- EXPERIENCIA LABORAL DINÁMICA -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Experiencia Laboral</h3>
                <div id="experiencias-wrapper">
                    @foreach($empleado->experienciasLaborales as $index => $exp)
                        <div class="border p-4 rounded mb-4 bg-gray-50 relative experiencia-row">
                            <!-- Input Oculto para identificar la experiencia al actualizar -->
                            <input type="hidden" name="experiencias[{{ $index }}][id]" value="{{ $exp->id }}">
                            
                            <button type="button" onclick="this.closest('.experiencia-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                                X Quitar
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div><label class="text-sm font-bold">Empresa *</label><input type="text" name="experiencias[{{ $index }}][empresa]" value="{{ $exp->empresa }}" class="w-full border rounded p-2" required></div>
                                <div><label class="text-sm font-bold">Puesto *</label><input type="text" name="experiencias[{{ $index }}][puesto]" value="{{ $exp->puesto }}" class="w-full border rounded p-2" required></div>
                                <div><label class="text-sm font-bold">Fecha Inicio</label><input type="date" name="experiencias[{{ $index }}][fecha_inicio]" value="{{ $exp->fecha_inicio }}" class="w-full border rounded p-2"></div>
                                <div><label class="text-sm font-bold">Fecha Fin</label><input type="date" name="experiencias[{{ $index }}][fecha_fin]" value="{{ $exp->fecha_fin }}" class="w-full border rounded p-2"></div>
                                
                                <div><label class="text-sm font-bold">Teléfono Empresa</label><input type="text" name="experiencias[{{ $index }}][telefono_empresa]" value="{{ $exp->telefono_empresa }}" class="w-full border rounded p-2"></div>
                                <div><label class="text-sm font-bold">Dirección Empresa</label><input type="text" name="experiencias[{{ $index }}][direccion_empresa]" value="{{ $exp->direccion_empresa }}" class="w-full border rounded p-2"></div>
                                <div><label class="text-sm font-bold">Primer Salario</label><input type="text" name="experiencias[{{ $index }}][primer_salario]" value="{{ $exp->primer_salario }}" class="w-full border rounded p-2"></div>
                                <div><label class="text-sm font-bold">Segundo Salario</label><input type="text" name="experiencias[{{ $index }}][segundo_salario]" value="{{ $exp->segundo_salario }}" class="w-full border rounded p-2"></div>
                                
                                <div class="col-span-2"><label class="text-sm font-bold">Motivo Retiro</label><input type="text" name="experiencias[{{ $index }}][motivo_retiro]" value="{{ $exp->motivo_retiro }}" class="w-full border rounded p-2"></div>
                                <div class="col-span-2"><label class="text-sm font-bold">Observaciones</label><input type="text" name="experiencias[{{ $index }}][observaciones]" value="{{ $exp->observaciones }}" class="w-full border rounded p-2"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarExperiencia()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Otra Experiencia
                </button>

                <!-- FORMACIÓN ACADÉMICA DINÁMICA -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Formación Académica</h3>
                <div id="formaciones-wrapper">
                    @foreach($empleado->formacionesAcademicas as $index => $formacion)
                        <div class="border p-4 rounded mb-4 bg-gray-50 relative formacion-row">
                            <input type="hidden" name="formaciones[{{ $index }}][id]" value="{{ $formacion->id }}">
                            <button type="button" onclick="this.closest('.formacion-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                                X Quitar
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-bold">Grado Académico *</label>
                                    <select name="formaciones[{{ $index }}][grado_academico_id]" class="w-full border rounded p-2" required>
                                        <option value="">Seleccione un grado...</option>
                                        @foreach($grados_academicos as $grado)
                                            <option value="{{ $grado->id }}" {{ $formacion->grado_academico_id == $grado->id ? 'selected' : '' }}>
                                                {{ $grado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><label class="text-sm font-bold">Institución *</label><input type="text" name="formaciones[{{ $index }}][institucion]" value="{{ $formacion->institucion }}" class="w-full border rounded p-2" required></div>
                                <div><label class="text-sm font-bold">Fecha Inicio</label><input type="date" name="formaciones[{{ $index }}][fecha_inicio]" value="{{ $formacion->fecha_inicio }}" class="w-full border rounded p-2"></div>
                                <div><label class="text-sm font-bold">Fecha Fin</label><input type="date" name="formaciones[{{ $index }}][fecha_fin]" value="{{ $formacion->fecha_fin }}" class="w-full border rounded p-2"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarFormacion()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Otra Formación
                </button>

                <!-- DOCUMENTOS DINÁMICOS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Documentos</h3>
                <div id="documentos-wrapper">
                    @foreach($empleado->documentos as $index => $doc)
                        <div class="border p-4 rounded mb-4 bg-gray-50 relative documento-row">
                            <input type="hidden" name="documentos[{{ $index }}][id]" value="{{ $doc->id }}">
                            <button type="button" onclick="this.closest('.documento-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                                X Quitar
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-sm font-bold">Tipo *</label>
                                    <select name="documentos[{{ $index }}][tipo_documento_id]" class="w-full border rounded p-2" required>
                                        <option value="">Seleccione un tipo...</option>
                                        @foreach($tipos_documento as $tipo)
                                            <option value="{{ $tipo->id }}" {{ $doc->tipo_documento_id == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><label class="text-sm font-bold">Descripción *</label><input type="text" name="documentos[{{ $index }}][descripcion]" value="{{ $doc->descripcion }}" class="w-full border rounded p-2" required></div>
                                <div><label class="text-sm font-bold">Fecha Emisión *</label><input type="date" name="documentos[{{ $index }}][fecha_emision]" value="{{ $doc->fecha_emision }}" class="w-full border rounded p-2" required></div>
                                <div><label class="text-sm font-bold">Fecha Vencimiento</label><input type="date" name="documentos[{{ $index }}][fecha_vencimiento]" value="{{ $doc->fecha_vencimiento }}" class="w-full border rounded p-2"></div>
                                
                                <div class="col-span-2">
                                    <label class="text-sm font-bold">Observaciones</label>
                                    <input type="text" name="documentos[{{ $index }}][observaciones]" value="{{ $doc->observaciones }}" class="w-full border rounded p-2">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm font-bold">Actualizar Archivo</label>
                                    <input type="file" name="documentos[{{ $index }}][archivo]" accept=".pdf, .jpg, .jpeg, .png" class="w-full border rounded p-1 text-sm bg-white">
                                    
                                    @if($doc->archivo_ruta)
                                        <div class="mt-2 text-xs">
                                            <span class="text-green-600 font-semibold">✓ Archivo actual: </span> 
                                            <a href="{{ asset('storage/' . $doc->archivo_ruta) }}" target="_blank" class="text-blue-500 hover:underline font-bold">Ver adjunto</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarDocumento()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Otro Documento
                </button>

                <!-- REFERENCIAS LABORALES DINÁMICAS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Referencias Laborales</h3>
                <div id="referencias-laborales-wrapper">
                    @foreach($empleado->referenciasLaborales as $index => $ref)
                        <div class="border p-4 rounded mb-4 bg-gray-50 relative ref-laboral-row">
                            <input type="hidden" name="referencias_laborales[{{ $index }}][id]" value="{{ $ref->id }}">
                            <button type="button" onclick="this.closest('.ref-laboral-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                                X Quitar
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-sm font-bold">Nombre del Contacto *</label>
                                    <input type="text" name="referencias_laborales[{{ $index }}][nombre]" value="{{ $ref->nombre }}" class="w-full border rounded p-2" required>
                                </div>
                                <div>
                                    <label class="text-sm font-bold">Empresa *</label>
                                    <input type="text" name="referencias_laborales[{{ $index }}][empresa]" value="{{ $ref->empresa }}" class="w-full border rounded p-2" required>
                                </div>
                                <div>
                                    <label class="text-sm font-bold">Teléfono *</label>
                                    <input type="text" name="referencias_laborales[{{ $index }}][telefono]" value="{{ $ref->telefono }}" class="w-full border rounded p-2" required>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarReferenciaLaboral()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Otra Referencia
                </button>

                <!-- REFERENCIAS PERSONALES DINÁMICAS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Referencias Personales</h3>
                <div id="referencias-personales-wrapper">
                    @foreach($empleado->referenciasPersonales as $index => $ref)
                        <div class="border p-4 rounded mb-4 bg-gray-50 relative ref-personal-row">
                            <input type="hidden" name="referencias_personales[{{ $index }}][id]" value="{{ $ref->id }}">
                            <button type="button" onclick="this.closest('.ref-personal-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                                X Quitar
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-sm font-bold">Nombre del Contacto *</label>
                                    <input type="text" name="referencias_personales[{{ $index }}][nombre]" value="{{ $ref->nombre }}" class="w-full border rounded p-2" required>
                                </div>
                                <div>
                                    <label class="text-sm font-bold">Empresa / Ocupación</label>
                                    <input type="text" name="referencias_personales[{{ $index }}][empresa]" value="{{ $ref->empresa }}" class="w-full border rounded p-2">
                                </div>
                                <div>
                                    <label class="text-sm font-bold">Teléfono *</label>
                                    <input type="text" name="referencias_personales[{{ $index }}][telefono]" value="{{ $ref->telefono }}" class="w-full border rounded p-2" required>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarReferenciaPersonal()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow mb-6">
                    + Añadir Otra Referencia Personal
                </button>

                </fieldset>

                @unless($soloLectura)
                <div class="flex justify-end mt-6">
                    <a href="{{ route('empleados.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">Actualizar Empleado</button>
                </div>
                @endunless
            </form>
        </div>
    </div>

    <script>
    // Iniciar el contador basándose en cuántos registros ya tiene el empleado cargados
    let expIndex = {{ count($empleado->experienciasLaborales) }};

    function agregarExperiencia() {
        const wrapper = document.getElementById('experiencias-wrapper');
        const template = `
            <div class="border p-4 rounded mb-4 bg-gray-50 relative experiencia-row">
                <button type="button" onclick="this.closest('.experiencia-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                    X Quitar
                </button>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div><label class="text-sm font-bold">Empresa *</label><input type="text" name="experiencias[${expIndex}][empresa]" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Puesto *</label><input type="text" name="experiencias[${expIndex}][puesto]" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Fecha Inicio</label><input type="date" name="experiencias[${expIndex}][fecha_inicio]" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Fecha Fin</label><input type="date" name="experiencias[${expIndex}][fecha_fin]" class="w-full border rounded p-2"></div>
                    
                    <div><label class="text-sm font-bold">Teléfono Empresa</label><input type="text" name="experiencias[${expIndex}][telefono_empresa]" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Dirección Empresa</label><input type="text" name="experiencias[${expIndex}][direccion_empresa]" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Primer Salario</label><input type="text" name="experiencias[${expIndex}][primer_salario]" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Segundo Salario</label><input type="text" name="experiencias[${expIndex}][segundo_salario]" class="w-full border rounded p-2"></div>
                    
                    <div class="col-span-2"><label class="text-sm font-bold">Motivo Retiro</label><input type="text" name="experiencias[${expIndex}][motivo_retiro]" class="w-full border rounded p-2"></div>
                    <div class="col-span-2"><label class="text-sm font-bold">Observaciones</label><input type="text" name="experiencias[${expIndex}][observaciones]" class="w-full border rounded p-2"></div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', template);
        expIndex++;
    }

    // --- Lógica de Formación Académica ---
    let formIndex = {{ count($empleado->formacionesAcademicas) }};
    const gradosAcademicos = @json($grados_academicos);

    function agregarFormacion() {
        const wrapper = document.getElementById('formaciones-wrapper');
        
        let opcionesGrados = '<option value="">Seleccione un grado...</option>';
        gradosAcademicos.forEach(grado => {
            opcionesGrados += `<option value="${grado.id}">${grado.nombre}</option>`;
        });

        const template = `
            <div class="border p-4 rounded mb-4 bg-gray-50 relative formacion-row">
                <button type="button" onclick="this.closest('.formacion-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                    X Quitar
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-bold">Grado Académico *</label>
                        <select name="formaciones[${formIndex}][grado_academico_id]" class="w-full border rounded p-2" required>
                            ${opcionesGrados}
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Institución *</label><input type="text" name="formaciones[${formIndex}][institucion]" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Fecha Inicio</label><input type="date" name="formaciones[${formIndex}][fecha_inicio]" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Fecha Fin</label><input type="date" name="formaciones[${formIndex}][fecha_fin]" class="w-full border rounded p-2"></div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', template);
        formIndex++;
    }

    // --- Lógica de Documentos ---
    let docIndex = {{ count($empleado->documentos) }};
    const tiposDocumento = @json($tipos_documento);

    function agregarDocumento() {
        const wrapper = document.getElementById('documentos-wrapper');
        
        let opcionesTipos = '<option value="">Seleccione un tipo...</option>';
        tiposDocumento.forEach(tipo => {
            opcionesTipos += `<option value="${tipo.id}">${tipo.nombre}</option>`;
        });

        const template = `
            <div class="border p-4 rounded mb-4 bg-gray-50 relative documento-row">
                <button type="button" onclick="this.closest('.documento-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                    X Quitar
                </button>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-bold">Tipo *</label>
                        <select name="documentos[${docIndex}][tipo_documento_id]" class="w-full border rounded p-2" required>
                            ${opcionesTipos}
                        </select>
                    </div>
                    <div><label class="text-sm font-bold">Descripción *</label><input type="text" name="documentos[${docIndex}][descripcion]" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Fecha Emisión *</label><input type="date" name="documentos[${docIndex}][fecha_emision]" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Fecha Vencimiento</label><input type="date" name="documentos[${docIndex}][fecha_vencimiento]" class="w-full border rounded p-2"></div>
                    
                    <div class="col-span-2">
                        <label class="text-sm font-bold">Observaciones</label>
                        <input type="text" name="documentos[${docIndex}][observaciones]" class="w-full border rounded p-2">
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm font-bold">Archivo adjunto</label>
                        <input type="file" name="documentos[${docIndex}][archivo]" accept=".pdf, .jpg, .jpeg, .png" class="w-full border rounded p-1 text-sm bg-white">
                    </div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', template);
        docIndex++;
    }

    let refLabIndex = {{ count($empleado->referenciasLaborales) }};

    function agregarReferenciaLaboral() {
        const wrapper = document.getElementById('referencias-laborales-wrapper');
        const template = `
            <div class="border p-4 rounded mb-4 bg-gray-50 relative ref-laboral-row">
                <button type="button" onclick="this.closest('.ref-laboral-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                    X Quitar
                </button>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold">Nombre del Contacto *</label>
                        <input type="text" name="referencias_laborales[${refLabIndex}][nombre]" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="text-sm font-bold">Empresa *</label>
                        <input type="text" name="referencias_laborales[${refLabIndex}][empresa]" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="text-sm font-bold">Teléfono *</label>
                        <input type="text" name="referencias_laborales[${refLabIndex}][telefono]" class="w-full border rounded p-2" required>
                    </div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', template);
        refLabIndex++;
    }

    let refPersIndex = {{ count($empleado->referenciasPersonales) }};

    function agregarReferenciaPersonal() {
        const wrapper = document.getElementById('referencias-personales-wrapper');
        const template = `
            <div class="border p-4 rounded mb-4 bg-gray-50 relative ref-personal-row">
                <button type="button" onclick="this.closest('.ref-personal-row').remove()" class="absolute top-2 right-2 text-red-600 hover:text-red-800 font-bold text-sm">
                    X Quitar
                </button>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold">Nombre del Contacto *</label>
                        <input type="text" name="referencias_personales[${refPersIndex}][nombre]" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="text-sm font-bold">Empresa / Ocupación</label>
                        <input type="text" name="referencias_personales[${refPersIndex}][empresa]" class="w-full border rounded p-2" placeholder="Opcional">
                    </div>
                    <div>
                        <label class="text-sm font-bold">Teléfono *</label>
                        <input type="text" name="referencias_personales[${refPersIndex}][telefono]" class="w-full border rounded p-2" required>
                    </div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', template);
        refPersIndex++;
    }
</script>
</x-app-layout>