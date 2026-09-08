<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// Asegúrate de importar todos los modelos necesarios aquí
use App\Models\{Profesion, TipoDocumento, GradoAcademico, Empresa,  TipoContrato, Turno, TipoSalario, Departamento, Genero, EstadoCivil, Religion, Pais, IgssDepartamento, Municipio, NivelEducativo, Pueblo, ComunidadLinguistica, TipoCasa, TipoTransporte, Discapacidad, Ocupacion, TipoPago, Moneda, Seguro, Banco, TipoCuenta, Puesto, IgssTipoPlanilla, CentroCosto, ManoObra, EmplDocIdentificacion};

class EmpleadoController extends Controller
{
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

        
        return view('empleados.edit', $catalogos);
    }

    public function update(Request $request, Empleado $empleado)
    {
        $booleans = ['genera_planilla', 'contratable', 'aplica_credito_fiscal_isr', 'vehiculo', 'salario_fijo', 'pertenece_asociacion', 'pension_alimentaria', 'embargo', 'marca_reloj'];
        $data = $request->all();
        
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

        //dd($empleado);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
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
