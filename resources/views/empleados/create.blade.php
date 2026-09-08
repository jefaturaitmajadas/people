<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Registrar Empleado</h2></x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('empleados.store') }}" method="POST" enctype="multipart/form-data">
            
                @csrf
                
                <!-- SECCIÓN BASE -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Información Corporativa</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">Empresa</label>
                        <select name="empresa_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($empresas as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Jefe</label>
                        <select name="jefe_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($jefes as $t)<option value="{{ $t->id }}">{{ $t->primer_nombre .' '.$t->primer_apellido }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Unidad de Negocio (Depto)</label>
                        <select name="departamento_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($departamentos as $d)<option value="{{ $d->id }}">{{ $d->nombre }}</option>@endforeach</select>
                    </div>
                </div>

                <!-- SECCIÓN BASE -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Información Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold text-gray-700">Primer Nombre *</label><input type="text" name="primer_nombre" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold text-gray-700">Segundo Nombre </label><input type="text" name="segundo_nombre" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold text-gray-700">Primer Apellido *</label><input type="text" name="primer_apellido" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold text-gray-700">Segundo Apellido </label><input type="text" name="segundo_apellido" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold text-gray-700">Apellido Casada</label><input type="text" name="casada_apellido" class="w-full border rounded p-2" ></div>
                    <div><label class="text-sm font-bold text-gray-700">Télefono</label><input type="text" name="telefono" class="w-full border rounded p-2" ></div>
                    <div><label class="text-sm font-bold text-gray-700">Télefono Casa</label><input type="text" name="telefono_casa" class="w-full border rounded p-2" ></div>
                    <div><label class="text-sm font-bold">Tipo de Identificación</label>
                        <select name="identificacion_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($tipo_identificaciones as $t)<option value="{{ $t->id }}">{{ $t->documento_identificacion }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold text-gray-700">Identificación</label><input type="text" name="identificacion" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Correo Corporativo</label><input type="email" name="correo_corporativo" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold text-gray-700">Correo Personal</label><input type="email" name="correo_personal" class="w-full border rounded p-2"></div>

                    <!-- FOTO DE PERFIL -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Fotografía</h3>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subir Foto de Perfil</label>
                    <input type="file" name="foto_perfil" accept="image/*" class="border rounded p-2 w-full md:w-1/2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Sube una imagen clara del empleado (Formatos recomendados: JPG, PNG).</p>
                </div>
                </div>

                <!-- GESTIÓN -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Gestión y Contrato</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">Codigo Empleado</label><input type="text" name="codigo_empleado" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Fecha Inicio *</label><input type="date" name="fecha_inicio" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">Fecha Salida</label><input type="date" name="fecha_salida" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Tipo Contrato</label>
                        <select name="tipo_contrato_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($tipos_contrato as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Turno</label>
                        <select name="turno_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($turnos as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach</select>
                    </div>
                    
                    <div class="flex gap-4 col-span-4 mt-2">
                        <label><input type="checkbox" name="genera_planilla" checked> Genera Planilla</label>
                        <label><input type="checkbox" name="contratable" checked> Contratable</label>
                    </div>
                </div>

                <!-- PERSONAL -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Datos Personales</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">NIT</label><input type="text" name="nit" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">No. IGSS</label><input type="text" name="no_igss" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">IRTRA</label><input type="text" name="irtra" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Género</label>
                        <select name="genero_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($generos as $g)<option value="{{ $g->id }}">{{ $g->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Estado Civil</label>
                        <select name="estado_civil_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($estados_civiles as $e)<option value="{{ $e->id }}">{{ $e->nombre }}</option>@endforeach</select>
                    </div>
                     <div><label class="text-sm font-bold">Religiones</label>
                        <select name="religion_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($religiones as $e)<option value="{{ $e->id }}">{{ $e->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Fecha Nacimiento *</label><input type="date" name="fecha_nacimiento" class="w-full border rounded p-2" required></div>
                    <div><label class="text-sm font-bold">País Nacimiento</label>
                        <select name="pais_nacimiento_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($paises as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Departamento Nacimiento</label>
                        <select name="departamento_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($ubicacion_departamentos as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Municipios Nacimiento</label>
                        <select name="municipio_nacimiento_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($municipios as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Pueblo</label>
                        <select name="pueblo_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($pueblos as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Comunidad Linguistica</label>
                        <select name="comunidad_linguistica_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($comunidades as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Nivel Educativo</label>
                        <select name="nivel_educativo_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($niveles_educativos as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>

                    <div><label class="text-sm font-bold">Profesión</label>
                        <select name="profesion_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($profesiones as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>

                    

                    <div><label class="text-sm font-bold">Contacto Emergencia</label><input type="text" name="contacto_emergencia" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono Emergencia</label><input type="text" name="telefono_contacto_emergencia" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">País Residencia</label>
                        <select name="pais_residencia_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($paises as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div class="col-span-4"><label class="text-sm font-bold">Dirección Exacta</label><textarea name="direccion_exacta" rows="2" class="w-full border rounded p-2"></textarea></div>

                    <div><label class="text-sm font-bold">Discapacidad</label>
                        <select name="discapacidad_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($discapacidades as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Ocupación</label>
                        <select name="ocupacion_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($ocupaciones as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>

                     <div class="flex gap-4 col-span-4 mt-2">
                        <label><input type="checkbox" name="aplica_credito_fiscal_isr" checked> Genera Crédito Fiscal ISR</label>
                        
                    </div>

                    <div><label class="text-sm font-bold">Vive Con</label><input type="text" name="vive_con" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Tipo de Vivienda</label>
                        <select name="tipo_casa_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($tipos_casa as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Tipo de Transporte</label>
                        <select name="tipo_transporte_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($tipos_transporte as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Placa</label><input type="text" name="vehiculo" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Cantidad Hijos</label><input type="number" name="cantidad_hijos" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Cantidad Dependientes</label><input type="number" name="cantidad_dependientes" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Nombre del padre</label><input type="text" name="nombre_padre" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono del padre</label><input type="text" name="telefono_padre" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Nombre de la madre</label><input type="text" name="nombre_madre" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono de la madre</label><input type="text" name="telefono_madre" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Nombre de Conyuge</label><input type="text" name="nombre_conyuge" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Teléfono de Conyuge</label><input type="text" name="telefono_conyuge" class="w-full border rounded p-2"></div>
                   

                </div>

                <!-- NÓMINA Y FINANZAS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2">Nómina y Finanzas</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div><label class="text-sm font-bold">Puesto</label>
                        <select name="puesto_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($puestos as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Centro de Costo</label>
                        <select name="centro_costo_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($centros_costo as $c)<option value="{{ $c->id }}">{{ $c->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Tipo Salario</label>
                        <select name="tipo_salario_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($tipos_salario as $b)<option value="{{ $b->id }}">{{ $b->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Moneda</label>
                        <select name="moneda_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($monedas as $p)<option value="{{ $p->id }}">{{ $p->simbolo.' '.$p->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Salario Base</label><input type="number" step="0.01" name="salario" class="w-full border rounded p-2"></div>
                    
                    <div class="flex items-center gap-2 mt-4"><input type="checkbox" name="salario_fijo"> <label>Salario fijo</label></div>
                    <div><label class="text-sm font-bold">Salario Por Hora</label><input type="number" step="0.01" name="salario_por_hora" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Banco</label>
                        <select name="banco_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($bancos as $b)<option value="{{ $b->id }}">{{ $b->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Tipo Cuenta</label>
                        <select name="tipo_cuenta_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($tipos_cuenta as $b)<option value="{{ $b->id }}">{{ $b->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Número de Cuenta</label><input type="text" name="numero_cuenta" class="w-full border rounded p-2"></div>
                    <div><label class="text-sm font-bold">Poliza</label><input type="text" name="poliza" class="w-full border rounded p-2"></div>

                    <div><label class="text-sm font-bold">Tipo Planilla</label>
                        <select name="igss_tipo_planilla_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($planillas as $b)<option value="{{ $b->id }}">{{ $b->nombre }}</option>@endforeach</select>
                    </div>
                    <div><label class="text-sm font-bold">Mano de Obra</label>
                        <select name="mano_obra_id" class="w-full border rounded p-2"><option value="">Seleccione</option>@foreach($manos_obra as $b)<option value="{{ $b->id }}">{{ $b->nombre }}</option>@endforeach</select>
                    </div>
                    
                    
                    <div class="flex items-center gap-2 mt-4"><input type="checkbox" name="marca_reloj"> <label>Marca Reloj</label></div>
                    <!-- Días Adicionales de Vacaciones -->
                    <div >
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
                    <!-- Aquí se inyectarán las filas con JavaScript -->
                </div>
                <button type="button" onclick="agregarExperiencia()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Experiencia
                </button>

                <!-- FORMACIÓN ACADÉMICA DINÁMICA -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Formación Académica</h3>
                <div id="formaciones-wrapper">
                    <!-- Aquí se inyectarán las filas con JavaScript -->
                </div>
                <button type="button" onclick="agregarFormacion()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Formación
                </button>

                <!-- DOCUMENTOS DINÁMICOS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Documentos</h3>
                <div id="documentos-wrapper">
                    <!-- Aquí se inyectarán las filas con JavaScript -->
                </div>
                <button type="button" onclick="agregarDocumento()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Documento
                </button>

                <!-- REFERENCIAS LABORALES DINÁMICAS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Referencias Laborales</h3>
                <div id="referencias-laborales-wrapper">
                    <!-- Aquí se inyectarán las filas con JavaScript -->
                </div>
                <button type="button" onclick="agregarReferenciaLaboral()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow">
                    + Añadir Referencia Laboral
                </button>

                <!-- REFERENCIAS PERSONALES DINÁMICAS -->
                <h3 class="text-lg font-bold text-blue-600 border-b mb-4 pb-2 mt-8">Referencias Personales</h3>
                <div id="referencias-personales-wrapper">
                    <!-- Aquí se inyectarán las filas con JavaScript -->
                </div>
                <button type="button" onclick="agregarReferenciaPersonal()" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm shadow mb-6">
                    + Añadir Referencia Personal
                </button>


                <div class="flex justify-end mt-6">
                    <a href="{{ route('empleados.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Cancelar</a>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Guardar Empleado</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let expIndex = 0;

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
    let formIndex = 0;
    
    // Convertimos el catálogo PHP a un objeto JavaScript
    const gradosAcademicos = @json($grados_academicos);

    function agregarFormacion() {
        const wrapper = document.getElementById('formaciones-wrapper');
        
        // Generamos las opciones del select dinámicamente
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

    let docIndex = 0;
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
                        <label class="text-sm font-bold">Archivo (Opcional)</label>
                        <input type="file" name="documentos[${docIndex}][archivo]" accept=".pdf, .jpg, .jpeg, .png" class="w-full border rounded p-1 text-sm bg-white">
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG o PNG. Max 5MB.</p>
                    </div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', template);
        docIndex++;
    }

    let refLabIndex = 0;

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

    let refPersIndex = 0;

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

    // Al cargar la página, si gustas puedes añadir una fila vacía automáticamente
    document.addEventListener("DOMContentLoaded", function() {
        agregarExperiencia();
        agregarFormacion(); 
        agregarDocumento(); 
        agregarReferenciaLaboral(); 
        agregarReferenciaPersonal(); 
    });

    
</script>

</x-app-layout>