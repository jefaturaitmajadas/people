<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// Asegúrate de importar todos los modelos necesarios aquí
use App\Models\{Profesion, TipoDocumento, GradoAcademico, Empresa,  TipoContrato, Turno, TipoSalario, Departamento, Genero, EstadoCivil, Religion, Pais, IgssDepartamento, Municipio, NivelEducativo, Pueblo, ComunidadLinguistica, TipoCasa, TipoTransporte, Discapacidad, Ocupacion, TipoPago, Moneda, Seguro, Banco, TipoCuenta, Puesto, IgssTipoPlanilla, CentroCosto, ManoObra, EmplDocIdentificacion};

class EmpleadoController extends Controller
{
    // Lo único que el colaborador puede cambiar de su ficha: Información Base y Datos Personales.
    // La identificación no está porque es su usuario de acceso; solo RRHH la cambia
    private const CAMPOS_COLABORADOR = [
        'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'casada_apellido',
        'telefono', 'telefono_casa', 'correo_corporativo', 'correo_personal',
        'nit', 'no_igss', 'irtra', 'genero_id', 'estado_civil_id', 'religion_id', 'fecha_nacimiento',
        'pais_nacimiento_id', 'municipio_nacimiento_id', 'pueblo_id', 'comunidad_linguistica_id',
        'nivel_educativo_id', 'profesion_id', 'contacto_emergencia', 'telefono_contacto_emergencia',
        'pais_residencia_id', 'direccion_exacta', 'discapacidad_id', 'ocupacion_id', 'vive_con',
        'tipo_casa_id', 'tipo_transporte_id', 'cantidad_hijos', 'cantidad_dependientes',
        'nombre_padre', 'telefono_padre', 'nombre_madre', 'telefono_madre', 'nombre_conyuge', 'telefono_conyuge',
    ];

    private const BOOLEANOS_COLABORADOR = ['aplica_credito_fiscal_isr', 'vehiculo'];

    // Columnas que el colaborador puede enviar en cada lista de su ficha
    private const FILAS_COLABORADOR = [
        'experiencias' => ['id', 'empresa', 'puesto', 'fecha_inicio', 'fecha_fin', 'telefono_empresa', 'direccion_empresa', 'primer_salario', 'segundo_salario', 'motivo_retiro', 'observaciones'],
        'formaciones' => ['id', 'grado_academico_id', 'institucion', 'fecha_inicio', 'fecha_fin'],
        'documentos' => ['id', 'tipo_documento_id', 'descripcion', 'fecha_emision', 'fecha_vencimiento', 'observaciones'],
        'referencias_laborales' => ['id', 'nombre', 'empresa', 'telefono'],
        'referencias_personales' => ['id', 'nombre', 'empresa', 'telefono'],
    ];

    public function index()
    {
        $empleados = Empleado::with(['puesto', 'departamento'])->get();
        return view('empleados.index', compact('empleados'));
    }

    private function cargarCatalogos()
    {
        return [
            'empresas' => Empresa::all(),
            'jefes' => Empleado::all(),
            'tipos_contrato' => TipoContrato::all(),
            'turnos' => Turno::all(),
            'tipos_salario' => TipoSalario::all(),
            'departamentos' => Departamento::all(),
            'generos' => Genero::all(),
            'estados_civiles' => EstadoCivil::all(),
            'religiones' => Religion::all(),
            'departamentos' => Departamento::all(),
            'paises' => Pais::all(),
            'municipios' => Municipio::all(),
            'niveles_educativos' => NivelEducativo::all(),
            'pueblos' => Pueblo::all(),
            'comunidades' => ComunidadLinguistica::all(),
            'tipos_casa' => TipoCasa::all(),
            'tipos_transporte' => TipoTransporte::all(),
            'discapacidades' => Discapacidad::all(),
            'ocupaciones' => Ocupacion::all(),
            'tipos_pago' => TipoPago::all(),
            'monedas' => Moneda::all(),
            'seguros' => Seguro::all(),
            'bancos' => Banco::all(),
            'tipos_cuenta' => TipoCuenta::all(),
            'puestos' => Puesto::all(),
            'planillas' => IgssTipoPlanilla::all(),
            'centros_costo' => CentroCosto::all(),
            'manos_obra' => ManoObra::all(),
            'tipo_identificaciones' => EmplDocIdentificacion::all(),
            'ubicacion_departamentos' => IgssDepartamento::all(),
            'grados_academicos' => GradoAcademico::all(),
            'tipos_documento' => TipoDocumento::all(),
            'profesiones' => Profesion::all(),
        ];
    }

    public function create()
    {
        
        return view('empleados.create', $this->cargarCatalogos());
    }

    public function store(Request $request)
    {
        // Limpiar los checkboxes no enviados (si no están en el request, son false)
        $booleans = ['genera_planilla', 'contratable', 'aplica_credito_fiscal_isr', 'vehiculo', 'salario_fijo', 'pertenece_asociacion', 'pension_alimentaria', 'embargo', 'marca_reloj'];
        $data = $request->all();
        foreach ($booleans as $field) {
            $data[$field] = $request->has($field);
        }

         if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('fotos_empleados', 'public');
        }

        $empleado = Empleado::create($data); // Cambia Empleado::create() por $empleado = Empleado::create()

        // --- NUEVA LÓGICA: Guardar Experiencias Laborales ---
        if ($request->has('experiencias')) {
            foreach ($request->experiencias as $exp) {
                // Solo guardamos si llenaron al menos la empresa y el puesto
                if (!empty($exp['empresa']) && !empty($exp['puesto'])) {
                    $empleado->experienciasLaborales()->create($exp);
                }
            }
        }

        // --- NUEVA LÓGICA: Guardar Formación Académica ---
        if ($request->has('formaciones')) {
            foreach ($request->formaciones as $formacion) {
                // Solo guardamos si seleccionaron un grado y escribieron la institución
                if (!empty($formacion['grado_academico_id']) && !empty($formacion['institucion'])) {
                    $empleado->formacionesAcademicas()->create($formacion);
                }
            }
        }

        // --- NUEVA LÓGICA: Guardar Documentos ---
        if ($request->has('documentos')) {
            foreach ($request->documentos as $doc) {
                // Verificamos que traiga lo obligatorio
                if (!empty($doc['tipo_documento_id']) && !empty($doc['descripcion'])) {
                    
                    // Si trae un archivo adjunto en esta fila
                    if (isset($doc['archivo']) && $doc['archivo'] instanceof \Illuminate\Http\UploadedFile) {
                        $doc['archivo_ruta'] = $doc['archivo']->store('documentos_empleados', 'public');
                    }
                    
                    $empleado->documentos()->create($doc);
                }
            }
        }

        // --- NUEVA LÓGICA: Guardar Referencias Laborales ---
        if ($request->has('referencias_laborales')) {
            foreach ($request->referencias_laborales as $ref) {
                // Verificamos que llenen al menos el nombre y la empresa
                if (!empty($ref['nombre']) && !empty($ref['empresa'])) {
                    $empleado->referenciasLaborales()->create($ref);
                }
            }
        }

        // --- NUEVA LÓGICA: Guardar Referencias Personales ---
        if ($request->has('referencias_personales')) {
            foreach ($request->referencias_personales as $ref) {
                // Verificamos que llenen al menos el nombre y el teléfono (empresa/ocupación es opcional)
                if (!empty($ref['nombre']) && !empty($ref['telefono'])) {
                    $empleado->referenciasPersonales()->create($ref);
                }
            }
        }

        User::sincronizarAccesoEmpleado($empleado);

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado exitosamente.');
    }

    public function edit(Empleado $empleado)
    {
        $empleado->load([
            'experienciasLaborales', 
            'formacionesAcademicas', 
            'documentos', 
            'referenciasLaborales', 
            'referenciasPersonales'
        ]);
        $catalogos = $this->cargarCatalogos();
        $catalogos['empleado'] = $empleado;
        $catalogos['esColaborador'] = auth()->user()->isEmpleado();
        
        return view('empleados.edit', $catalogos);
    }

    public function update(Request $request, Empleado $empleado)
    {
        $esColaborador = $request->user()->isEmpleado();

        $booleans = ['genera_planilla', 'contratable', 'aplica_credito_fiscal_isr', 'vehiculo', 'salario_fijo', 'pertenece_asociacion', 'pension_alimentaria', 'embargo', 'marca_reloj'];
        $data = $request->all();

        if ($esColaborador) {
            $this->validarColaborador($request);

            $booleans = self::BOOLEANOS_COLABORADOR;
            $data = $request->only(self::CAMPOS_COLABORADOR);
            $data['segundo_apellido'] = $data['segundo_apellido'] ?? '';

            // Se descarta cualquier otra columna enviada en las listas, como empleado_id o archivo_ruta
            foreach (self::FILAS_COLABORADOR as $lista => $columnas) {
                if ($request->has($lista)) {
                    $request->merge([$lista => array_map(fn ($fila) => array_intersect_key((array) $fila, array_flip($columnas)), (array) $request->input($lista, []))]);
                }
            }
        }

        foreach ($booleans as $field) {
            $data[$field] = $request->has($field);
        }

        if ($request->hasFile('foto_perfil')) {
            // Eliminar la foto anterior del servidor si existe
            if ($empleado->foto_perfil) {
                Storage::disk('public')->delete($empleado->foto_perfil);
            }
            $data['foto_perfil'] = $request->file('foto_perfil')->store('fotos_empleados', 'public');
        }

        $empleado->update($data);

        // --- NUEVA LÓGICA: Sincronizar Experiencias Laborales ---
        if ($request->has('experiencias')) {
            // Obtenemos los IDs que vienen en el formulario
            $idsRecibidos = collect($request->experiencias)->pluck('id')->filter()->toArray();
            
            // Eliminamos las experiencias que estaban en BD pero que el usuario quitó (con la X)
            $empleado->experienciasLaborales()->whereNotIn('id', $idsRecibidos)->delete();

            // Recorremos las experiencias enviadas
            foreach ($request->experiencias as $exp) {
                if (!empty($exp['empresa']) && !empty($exp['puesto'])) {
                    if (isset($exp['id']) && $exp['id']) {
                        // Si tiene ID, actualizamos la existente
                        $empleado->experienciasLaborales()->where('id', $exp['id'])->update($exp);
                    } else {
                        // Si no tiene ID, es una nueva fila agregada en modo edición
                        $empleado->experienciasLaborales()->create($exp);
                    }
                }
            }
        } else {
            // Si mandó el formulario sin ninguna experiencia, borramos todas las que tenía
            $empleado->experienciasLaborales()->delete();
        }

        // --- NUEVA LÓGICA: Sincronizar Formación Académica ---
        if ($request->has('formaciones')) {
            $idsFormaciones = collect($request->formaciones)->pluck('id')->filter()->toArray();
            $empleado->formacionesAcademicas()->whereNotIn('id', $idsFormaciones)->delete();

            foreach ($request->formaciones as $formacion) {
                if (!empty($formacion['grado_academico_id']) && !empty($formacion['institucion'])) {
                    if (isset($formacion['id']) && $formacion['id']) {
                        $empleado->formacionesAcademicas()->where('id', $formacion['id'])->update($formacion);
                    } else {
                        $empleado->formacionesAcademicas()->create($formacion);
                    }
                }
            }
        } else {
            $empleado->formacionesAcademicas()->delete();
        }

        // --- NUEVA LÓGICA: Sincronizar Documentos ---
        if ($request->has('documentos')) {
            $idsDocumentos = collect($request->documentos)->pluck('id')->filter()->toArray();

            // 1. Obtener los documentos a eliminar para borrar sus archivos físicos del servidor
            $docsAEliminar = $empleado->documentos()->whereNotIn('id', $idsDocumentos)->get();
            foreach ($docsAEliminar as $docE) {
                if ($docE->archivo_ruta) {
                    Storage::disk('public')->delete($docE->archivo_ruta);
                }
                $docE->delete();
            }

            // 2. Recorrer los enviados en el formulario para actualizar o crear
            foreach ($request->documentos as $doc) {
                if (!empty($doc['tipo_documento_id']) && !empty($doc['descripcion'])) {
                    
                    if (isset($doc['id']) && $doc['id']) {
                        // Actualizando existente
                        $documentoExistente = $empleado->documentos()->find($doc['id']);
                        
                        // Si se subió un nuevo archivo para reemplazar el anterior
                        if (isset($doc['archivo']) && $doc['archivo'] instanceof \Illuminate\Http\UploadedFile) {
                            if ($documentoExistente->archivo_ruta) {
                                Storage::disk('public')->delete($documentoExistente->archivo_ruta);
                            }
                            $doc['archivo_ruta'] = $doc['archivo']->store('documentos_empleados', 'public');
                        }
                        
                        $documentoExistente->update($doc);
                    } else {
                        // Creando uno nuevo durante la edición
                        if (isset($doc['archivo']) && $doc['archivo'] instanceof \Illuminate\Http\UploadedFile) {
                            $doc['archivo_ruta'] = $doc['archivo']->store('documentos_empleados', 'public');
                        }
                        $empleado->documentos()->create($doc);
                    }
                }
            }
        } else {
            // Si no se envió ningún documento, borramos todos
            foreach ($empleado->documentos as $docE) {
                if ($docE->archivo_ruta) {
                    Storage::disk('public')->delete($docE->archivo_ruta);
                }
                $docE->delete();
            }
        }

        // --- NUEVA LÓGICA: Sincronizar Referencias Laborales ---
        if ($request->has('referencias_laborales')) {
            $idsRefLaborales = collect($request->referencias_laborales)->pluck('id')->filter()->toArray();
            $empleado->referenciasLaborales()->whereNotIn('id', $idsRefLaborales)->delete();

            foreach ($request->referencias_laborales as $ref) {
                if (!empty($ref['nombre']) && !empty($ref['empresa'])) {
                    if (isset($ref['id']) && $ref['id']) {
                        $empleado->referenciasLaborales()->where('id', $ref['id'])->update($ref);
                    } else {
                        $empleado->referenciasLaborales()->create($ref);
                    }
                }
            }
        } else {
            // Si eliminó todas en el formulario, las borramos de la base de datos
            $empleado->referenciasLaborales()->delete();
        }

        // --- NUEVA LÓGICA: Sincronizar Referencias Personales ---
        if ($request->has('referencias_personales')) {
            $idsRefPersonales = collect($request->referencias_personales)->pluck('id')->filter()->toArray();
            $empleado->referenciasPersonales()->whereNotIn('id', $idsRefPersonales)->delete();

            foreach ($request->referencias_personales as $ref) {
                if (!empty($ref['nombre']) && !empty($ref['telefono'])) {
                    if (isset($ref['id']) && $ref['id']) {
                        $empleado->referenciasPersonales()->where('id', $ref['id'])->update($ref);
                    } else {
                        $empleado->referenciasPersonales()->create($ref);
                    }
                }
            }
        } else {
            $empleado->referenciasPersonales()->delete();
        }

        User::sincronizarAccesoEmpleado($empleado);

        if ($esColaborador) {
            return redirect()->route('empleados.edit', $empleado->id)->with('success', 'Tus datos se guardaron correctamente.');
        }

        //dd($empleado);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    private function validarColaborador(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'correo_corporativo' => 'nullable|email|max:255',
            'correo_personal' => 'nullable|email|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'cantidad_hijos' => 'nullable|integer|min:0',
            'cantidad_dependientes' => 'nullable|integer|min:0',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentos.*.archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
            'documentos.*.fecha_emision' => 'required_with:documentos.*.descripcion|nullable|date',
            'referencias_laborales.*.telefono' => 'required_with:referencias_laborales.*.nombre',
        ], [
            'primer_nombre.required' => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'correo_corporativo.email' => 'El correo corporativo no es válido.',
            'correo_personal.email' => 'El correo personal no es válido.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',
            'foto_perfil.image' => 'La foto debe ser una imagen.',
            'foto_perfil.mimes' => 'La foto debe ser JPG, PNG o WEBP.',
            'foto_perfil.max' => 'La foto no puede pesar más de 5 MB.',
            'documentos.*.fecha_emision.required_with' => 'Escribe la fecha de emisión de cada documento.',
            'referencias_laborales.*.telefono.required_with' => 'Escribe el teléfono de cada referencia laboral.',
            'documentos.*.archivo.mimes' => 'Los documentos deben ser PDF, imagen, Word o Excel.',
            'documentos.*.archivo.max' => 'Cada documento puede pesar máximo 10 MB.',
        ]);
    }

    public function restablecerAcceso(Empleado $empleado)
    {
        $user = User::sincronizarAccesoEmpleado($empleado);

        if (!$user) {
            return back()->with('error', 'No se puede dar acceso: el colaborador no tiene identificación, ya tiene fecha de baja o su identificación está repetida.');
        }

        $user->update(['password' => $user->usuario, 'debe_cambiar_password' => true]);

        return back()->with('success', 'Acceso restablecido. La clave vuelve a ser la identificación y se pedirá cambiarla al ingresar.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }

    public function reporte(Empleado $empleado)
    {
        $empleado->load('detalleCargas.carga');
        $detalles = $empleado->detalleCargas->sortByDesc('carga.fecha_carga');
        return view('empleados.reporte', compact('empleado', 'detalles'));
    }

    

    public function constancia(Empleado $empleado)
    {
        // 1. Obtener la empresa activa
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return back()->with('error', 'Debes tener una empresa activa para generar la constancia.');
        }

        // 2. Formateador de números a letras (Requiere PHP intl extension)
        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);

        // 3. Preparar variables dinámicas
        $salarioBase = $empleado->salario ?? 0;
        
        // Convertimos el salario a letras y lo pasamos a mayúsculas
        $salarioLetras = mb_strtoupper($formatter->format(floor($salarioBase)), 'UTF-8');
        
        // Extraemos los centavos
        $centavos = explode('.', number_format($salarioBase, 2, '.', ''))[1];

        // Formato de fechas
        \Carbon\Carbon::setLocale('es');
        $fechaInicio = \Carbon\Carbon::parse($empleado->fecha_inicio)->translatedFormat('d \d\e F \d\e Y');
        
        $diaActual = date('d');
        $diaLetras = strtolower($formatter->format($diaActual));
        $mesActual = strtolower(\Carbon\Carbon::now()->translatedFormat('F'));
        $anioActual = date('Y');

        return view('empleados.constancia', compact(
            'empleado', 'empresa', 'salarioBase', 'salarioLetras', 'centavos', 
            'fechaInicio', 'diaLetras', 'mesActual', 'anioActual'
        ));
    }

    public function constanciaLaboral(Empleado $empleado)
    {
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return back()->with('error', 'Debes tener una empresa activa para generar la constancia.');
        }

        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);

        $salarioBase = $empleado->salario ?? 0;
        
        $salarioLetras = mb_strtoupper($formatter->format(floor($salarioBase)), 'UTF-8');
        $centavos = explode('.', number_format($salarioBase, 2, '.', ''))[1];

        \Carbon\Carbon::setLocale('es');
        $fechaInicio = \Carbon\Carbon::parse($empleado->fecha_inicio)->translatedFormat('d \d\e F \d\e Y');
        
        // Verificamos si tiene fecha de baja para cambiar la redacción de la carta
        $fechaBaja = null;
        if (!empty($empleado->fecha_salida)) {
            $fechaBaja = \Carbon\Carbon::parse($empleado->fecha_salida)->translatedFormat('d \d\e F \d\e Y');
        }
        
        $diaActual = date('d');
        $diaLetras = strtolower($formatter->format($diaActual));
        $mesActual = strtolower(\Carbon\Carbon::now()->translatedFormat('F'));
        $anioActual = date('Y');

        return view('empleados.constancia_laboral', compact(
            'empleado', 'empresa', 'salarioBase', 'salarioLetras', 'centavos', 
            'fechaInicio', 'fechaBaja', 'diaLetras', 'mesActual', 'anioActual'
        ));
    }

    public function constanciaPatronal(Empleado $empleado)
    {
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return back()->with('error', 'Debes tener una empresa activa.');
        }

        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
        $salarioBase = $empleado->salario ?? 0;
        $salarioLetras = mb_strtoupper($formatter->format(floor($salarioBase)), 'UTF-8');
        $centavos = explode('.', number_format($salarioBase, 2, '.', ''))[1];

        \Carbon\Carbon::setLocale('es');
        $fechaInicio = \Carbon\Carbon::parse($empleado->fecha_inicio)->translatedFormat('d \d\e F \d\e Y');
        
        $fechaBaja = null;
        if (!empty($empleado->fecha_salida)) {
            $fechaBaja = \Carbon\Carbon::parse($empleado->fecha_salida)->translatedFormat('d \d\e F \d\e Y');
        }
        
        $diaActual = date('d');
        $diaLetras = strtolower($formatter->format($diaActual));
        $mesActual = strtolower(\Carbon\Carbon::now()->translatedFormat('F'));
        $anioActual = date('Y');

        return view('empleados.constancia_patronal', compact(
            'empleado', 'empresa', 'salarioBase', 'salarioLetras', 'centavos', 
            'fechaInicio', 'fechaBaja', 'diaLetras', 'mesActual', 'anioActual'
        ));
    }

    public function proyeccionIndemnizacion(Empleado $empleado)
    {
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return back()->with('error', 'Debes tener una empresa activa.');
        }

        \Carbon\Carbon::setLocale('es');
        $hoy = \Carbon\Carbon::now();
        $fechaInicio = \Carbon\Carbon::parse($empleado->fecha_inicio);
        
        // 1. Cálculo de tiempo laborado
        // Si ya tiene fecha de baja, usamos esa. Si no, usamos el día de hoy (proyección)
        $fechaCalculo = $empleado->fecha_salida ? \Carbon\Carbon::parse($empleado->fecha_salida) : $hoy;
        
        // Se suma 1 día para ser inclusivos (el día que entra cuenta como trabajado)
        $diasLaborados = $fechaInicio->diffInDays($fechaCalculo) + 1;
        
        // Calcular Años, Meses y Días para mostrar en el reporte de forma amigable
        $anios = $fechaInicio->diffInYears($fechaCalculo);
        $meses = $fechaInicio->copy()->addYears($anios)->diffInMonths($fechaCalculo);
        $diasRestantes = $fechaInicio->copy()->addYears($anios)->addMonths($meses)->diffInDays($fechaCalculo);
        $tiempoFormateado = "{$anios} años, {$meses} meses y {$diasRestantes} días";

        // 2. Integración Salarial (Ley de Guatemala: Base + 1/12 Aguinaldo + 1/12 Bono 14)
        $salarioBase = $empleado->salario ?? 0;
        $proporcionAguinaldo = $salarioBase / 12;
        $proporcionBono14 = $salarioBase / 12;
        $salarioIntegrado = $salarioBase + $proporcionAguinaldo + $proporcionBono14;

        // 3. Cálculo Final de la Indemnización
        // Fórmula: (Salario Integrado / 365) * Días totales laborados
        $indemnizacionTotal = ($salarioIntegrado / 365) * $diasLaborados;

        // Formatos de fecha en texto
        $fechaInicioTxt = $fechaInicio->translatedFormat('d \d\e F \d\e Y');
        $fechaCalculoTxt = $fechaCalculo->translatedFormat('d \d\e F \d\e Y');

        return view('empleados.proyeccion_indemnizacion', compact(
            'empleado', 'empresa', 'salarioBase', 'salarioIntegrado', 
            'proporcionAguinaldo', 'proporcionBono14', 'diasLaborados', 
            'tiempoFormateado', 'indemnizacionTotal', 'fechaInicioTxt', 'fechaCalculoTxt'
        ));
    }
}
