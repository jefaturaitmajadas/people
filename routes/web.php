<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CargaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\TipoIdentificacionController;
use App\Http\Controllers\IgssActividadEconomicaController;
use App\Http\Controllers\IgssPaisController;
use App\Http\Controllers\EmplNacionalidadController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\EmplTipoDiscapacidadController;
use App\Http\Controllers\DiscapacidadController;
use App\Http\Controllers\IgssOcupacionController;
use App\Http\Controllers\EmpleOcupacionController;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\DetalleCarga;
use App\Http\Controllers\OcupacionController;
use App\Http\Controllers\EmplSexoController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\EmplEstadoCivilController;
use App\Http\Controllers\EstadoCivilController;
use App\Http\Controllers\EmplJornadaTrabajoController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\EmplDocIdentificacionController;
use App\Http\Controllers\EmplTemporalidadContratoController;
use App\Http\Controllers\TipoContratoController;
use App\Http\Controllers\EmplTipoContratoController;
use App\Http\Controllers\EmplNivelEducativoController;
use App\Http\Controllers\NivelEducativoController;
use App\Http\Controllers\EmplPuebloPertenenciaController;
use App\Http\Controllers\PuebloController;
use App\Http\Controllers\EmplComunidadLinguisticaController;
use App\Http\Controllers\ComunidadLinguisticaController;
use App\Http\Controllers\IgssTipoSalarioController;
use App\Http\Controllers\TipoSalarioController;
use App\Http\Controllers\IgssDepartamentoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\IgssTipoPlanillaController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\SeguroController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\TipoCuentaController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\CentroCostoController;
use App\Http\Controllers\ManoObraController;
use App\Http\Controllers\TituloAcademicoController;
use App\Http\Controllers\GradoAcademicoController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\TipoCasaController;
use App\Http\Controllers\TipoTransporteController;
use App\Http\Controllers\TipoIncapacidadController;
use App\Http\Controllers\TipoSuspensionController;
use App\Http\Controllers\TipoVacacionesController;
use App\Http\Controllers\TipoPermisoController;
use App\Http\Controllers\ExperienciaLaboralController;
use App\Http\Controllers\FormacionAcademicaController;
use App\Http\Controllers\DocumentoEmpleadoController;
use App\Http\Controllers\ReferenciaLaboralController;
use App\Http\Controllers\ReferenciaPersonalController;
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\TipoDescuentoController;
use App\Http\Controllers\DescuentoCuotaController;
use App\Http\Controllers\TipoIngresoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\IngresoPagoController;
use App\Http\Controllers\HoraExtraController;
use App\Http\Controllers\TipoIncidenciaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\EntornoController;
use App\Http\Controllers\ProfesionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // El nuevo Dashboard es el selector de empresas
    Route::get('/dashboard', [EntornoController::class, 'seleccionarEntorno'])->name('dashboard');
    Route::post('/entorno/activar/{empresa}', [EntornoController::class, 'activarEntorno'])->name('entorno.activar');

    Route::resource('users', App\Http\Controllers\UserController::class);

});


// 2. RUTAS OPERATIVAS (Protegidas por el guardia del entorno)
Route::middleware(['auth',  'entorno.activo'])->group(function () {

    Route::get('empleados/{empleado}/experiencia-laboral', [ExperienciaLaboralController::class, 'index'])->name('experiencia-laboral.index');
    Route::get('empleados/{empleado}/experiencia-laboral/create', [ExperienciaLaboralController::class, 'create'])->name('experiencia-laboral.create');
    Route::post('empleados/{empleado}/experiencia-laboral', [ExperienciaLaboralController::class, 'store'])->name('experiencia-laboral.store');
    
    // Para editar y eliminar solo necesitamos el ID de la experiencia
    Route::get('experiencia-laboral/{experiencia}/edit', [ExperienciaLaboralController::class, 'edit'])->name('experiencia-laboral.edit');
    Route::put('experiencia-laboral/{experiencia}', [ExperienciaLaboralController::class, 'update'])->name('experiencia-laboral.update');
    Route::delete('experiencia-laboral/{experiencia}', [ExperienciaLaboralController::class, 'destroy'])->name('experiencia-laboral.destroy');

   

    Route::get('empleados/{empleado}/formacion-academica', [FormacionAcademicaController::class, 'index'])->name('formacion-academica.index');
    Route::get('empleados/{empleado}/formacion-academica/create', [FormacionAcademicaController::class, 'create'])->name('formacion-academica.create');
    Route::post('empleados/{empleado}/formacion-academica', [FormacionAcademicaController::class, 'store'])->name('formacion-academica.store');
    
    Route::get('formacion-academica/{formacion}/edit', [FormacionAcademicaController::class, 'edit'])->name('formacion-academica.edit');
    Route::put('formacion-academica/{formacion}', [FormacionAcademicaController::class, 'update'])->name('formacion-academica.update');
    Route::delete('formacion-academica/{formacion}', [FormacionAcademicaController::class, 'destroy'])->name('formacion-academica.destroy');

    Route::get('empleados/{empleado}/documentos', [DocumentoEmpleadoController::class, 'index'])->name('documentos.index');
    Route::get('empleados/{empleado}/documentos/create', [DocumentoEmpleadoController::class, 'create'])->name('documentos.create');
    Route::post('empleados/{empleado}/documentos', [DocumentoEmpleadoController::class, 'store'])->name('documentos.store');
    
    Route::get('documentos/{documento}/edit', [DocumentoEmpleadoController::class, 'edit'])->name('documentos.edit');
    Route::put('documentos/{documento}', [DocumentoEmpleadoController::class, 'update'])->name('documentos.update');
    Route::delete('documentos/{documento}', [DocumentoEmpleadoController::class, 'destroy'])->name('documentos.destroy');

    Route::get('empleados/{empleado}/referencia-laboral', [ReferenciaLaboralController::class, 'index'])->name('referencia-laboral.index');
    Route::get('empleados/{empleado}/referencia-laboral/create', [ReferenciaLaboralController::class, 'create'])->name('referencia-laboral.create');
    Route::post('empleados/{empleado}/referencia-laboral', [ReferenciaLaboralController::class, 'store'])->name('referencia-laboral.store');
    
    Route::get('referencia-laboral/{referencia}/edit', [ReferenciaLaboralController::class, 'edit'])->name('referencia-laboral.edit');
    Route::put('referencia-laboral/{referencia}', [ReferenciaLaboralController::class, 'update'])->name('referencia-laboral.update');
    Route::delete('referencia-laboral/{referencia}', [ReferenciaLaboralController::class, 'destroy'])->name('referencia-laboral.destroy');

    Route::get('empleados/{empleado}/referencia-personal', [ReferenciaPersonalController::class, 'index'])->name('referencia-personal.index');
    Route::get('empleados/{empleado}/referencia-personal/create', [ReferenciaPersonalController::class, 'create'])->name('referencia-personal.create');
    Route::post('empleados/{empleado}/referencia-personal', [ReferenciaPersonalController::class, 'store'])->name('referencia-personal.store');
    
    Route::get('referencia-personal/{referencia}/edit', [ReferenciaPersonalController::class, 'edit'])->name('referencia-personal.edit');
    Route::put('referencia-personal/{referencia}', [ReferenciaPersonalController::class, 'update'])->name('referencia-personal.update');
    Route::delete('referencia-personal/{referencia}', [ReferenciaPersonalController::class, 'destroy'])->name('referencia-personal.destroy');

    Route::get('empleados/{empleado}/descuentos', [DescuentoController::class, 'index'])->name('descuentos.index');
    Route::get('empleados/{empleado}/descuentos/create', [DescuentoController::class, 'create'])->name('descuentos.create');
    Route::post('empleados/{empleado}/descuentos', [DescuentoController::class, 'store'])->name('descuentos.store');
    
    Route::get('descuentos/{descuento}/edit', [DescuentoController::class, 'edit'])->name('descuentos.edit');
    Route::put('descuentos/{descuento}', [DescuentoController::class, 'update'])->name('descuentos.update');
    Route::delete('descuentos/{descuento}', [DescuentoController::class, 'destroy'])->name('descuentos.destroy');

   

    Route::get('descuentos/{descuento}/cuotas', [DescuentoCuotaController::class, 'index'])->name('descuento-cuotas.index');
    
    // Marcar una cuota como pagada manualmente (abono extra o pago en efectivo)
    Route::post('cuotas/{cuota}/pagar-manual', [DescuentoCuotaController::class, 'pagarManual'])->name('descuento-cuotas.pagar-manual');

    

    // CRUD de Ingresos del Empleado
    Route::get('empleados/{empleado}/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
    Route::get('empleados/{empleado}/ingresos/create', [IngresoController::class, 'create'])->name('ingresos.create');
    Route::post('empleados/{empleado}/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::get('ingresos/{ingreso}/edit', [IngresoController::class, 'edit'])->name('ingresos.edit');
    Route::put('ingresos/{ingreso}', [IngresoController::class, 'update'])->name('ingresos.update');
    Route::delete('ingresos/{ingreso}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');

    // Ver Pagos Proyectados (Estado de cuenta del ingreso)
    Route::get('ingresos/{ingreso}/pagos', [IngresoPagoController::class, 'index'])->name('ingreso-pagos.index');

    Route::get('empleados/{empleado}/horas-extras', [HoraExtraController::class, 'index'])->name('horas-extras.index');
    Route::get('empleados/{empleado}/horas-extras/create', [HoraExtraController::class, 'create'])->name('horas-extras.create');
    Route::post('empleados/{empleado}/horas-extras', [HoraExtraController::class, 'store'])->name('horas-extras.store');
    Route::delete('horas-extras/{hora_extra}', [HoraExtraController::class, 'destroy'])->name('horas-extras.destroy');

     Route::get('empleados/{empleado}/incidencias', [IncidenciaController::class, 'index'])->name('incidencias.index');
    Route::get('empleados/{empleado}/incidencias/create', [IncidenciaController::class, 'create'])->name('incidencias.create');
    Route::post('empleados/{empleado}/incidencias', [IncidenciaController::class, 'store'])->name('incidencias.store');
    
    

   
   // Rutas directas por ID de Incidencia (igual que en ingresos y descuentos)
    Route::get('incidencias/{incidencia}/edit', [IncidenciaController::class, 'edit'])->name('incidencias.edit');
    Route::put('incidencias/{incidencia}', [IncidenciaController::class, 'update'])->name('incidencias.update');
    Route::delete('incidencias/{incidencia}', [IncidenciaController::class, 'destroy'])->name('incidencias.destroy');

    Route::resource('planillas', PlanillaController::class);
    Route::post('planillas/{planilla}/cerrar', [PlanillaController::class, 'cerrarPlanilla'])->name('planillas.cerrar');

    // Ruta para el Recibo Individual
    Route::get('planilla-detalles/{detalle}/recibo', [PlanillaController::class, 'recibo'])->name('planillas.recibo');

    Route::get('/empleados/{empleado}/reporte', [EmpleadoController::class, 'reporte'])->name('empleados.reporte');
    Route::get('/empleados/{empleado}/constancia', [EmpleadoController::class, 'constancia'])->name('empleados.constancia');

    Route::get('planillas/{planilla}/archivo-bancos', [PlanillaController::class, 'exportarBancos'])->name('planillas.bancos');
    Route::resource('empresa-cuentas', App\Http\Controllers\EmpresaCuentaController::class)
         ->parameters(['empresa-cuentas' => 'empresaCuenta']);

    
    Route::get('planillas/{planilla}/archivo-igss', [PlanillaController::class, 'exportarIgss'])->name('planillas.igss');
    Route::get('/empleados/{empleado}/constancia-laboral', [EmpleadoController::class, 'constanciaLaboral'])->name('empleados.constancia_laboral');
    Route::get('/empleados/{empleado}/constancia-patronal', [EmpleadoController::class, 'constanciaPatronal'])->name('empleados.constancia_patronal');
    Route::get('/empleados/{empleado}/proyeccion-indemnizacion', [EmpleadoController::class, 'proyeccionIndemnizacion'])->name('empleados.proyeccion_indemnizacion');

    Route::get('/reportes/empleador', [App\Http\Controllers\ReporteController::class, 'reporteEmpleador'])->name('reportes.empleador');

    // Gestión de Vacaciones por Empleado
    Route::get('/empleados/{empleado}/vacaciones', [App\Http\Controllers\EmpleadoVacacionController::class, 'index'])->name('empleados.vacaciones.index');
    Route::post('/empleados/{empleado}/vacaciones', [App\Http\Controllers\EmpleadoVacacionController::class, 'store'])->name('empleados.vacaciones.store');
    Route::delete('/vacaciones/{vacacion}', [App\Http\Controllers\EmpleadoVacacionController::class, 'destroy'])->name('empleados.vacaciones.destroy');

    Route::get('/reportes/vacaciones', [App\Http\Controllers\ReporteController::class, 'reporteVacaciones'])->name('reportes.vacaciones');
    Route::get('/reportes/vacaciones/excel', [App\Http\Controllers\ReporteController::class, 'exportarVacacionesExcel'])->name('reportes.vacaciones.excel');

    // Gestión de Préstamos
    Route::get('/empleados/{empleado}/prestamos', [App\Http\Controllers\PrestamoController::class, 'index'])->name('empleados.prestamos.index');
    Route::post('/empleados/{empleado}/prestamos', [App\Http\Controllers\PrestamoController::class, 'store'])->name('empleados.prestamos.store');
    Route::get('/prestamos/{prestamo}/contrato', [App\Http\Controllers\PrestamoController::class, 'contrato'])->name('prestamos.contrato');
    Route::post('/prestamos/{prestamo}/aprobar', [App\Http\Controllers\PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');

    Route::get('/prestamos/{prestamo}/estado-cuenta', [App\Http\Controllers\PrestamoController::class, 'estadoCuenta'])->name('prestamos.estado_cuenta');

    Route::delete('/prestamos/{prestamo}', [App\Http\Controllers\PrestamoController::class, 'destroy'])->name('prestamos.destroy');

    // Cumpleañeros del mes
    Route::get('/reportes/cumpleanos', [App\Http\Controllers\CumpleanosController::class, 'index'])->name('reportes.cumpleanos');
    Route::get('/reportes/cumpleanos/arte', [App\Http\Controllers\CumpleanosController::class, 'arte'])->name('reportes.cumpleanos.arte');
    Route::get('/empleados/{empleado}/cumpleanos/correo', [App\Http\Controllers\CumpleanosController::class, 'previewCorreo'])->name('empleados.cumpleanos.correo');

   
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('tipo-descuentos', TipoDescuentoController::class);
    Route::resource('tipo-ingresos', TipoIngresoController::class);
 // Catálogo Tipos de Incidencias
    Route::resource('tipo-incidencias', TipoIncidenciaController::class)->except(['show']);

    Route::get('/empresas/{empresa}/empleados', [EmpresaController::class, 'empleados'])->name('empresas.empleados');
    Route::resource('empresas', EmpresaController::class);
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('tipo_identificacions', TipoIdentificacionController::class);
    
    Route::resource('empleados', EmpleadoController::class);
    //Route::resource('cargas', CargaController::class)->except(['edit', 'update']);
    Route::resource('actividad-economica', IgssActividadEconomicaController::class);
    Route::resource('igss-paises', IgssPaisController::class)->parameters([
        'igss-paises' => 'pais'
    ]);
    Route::resource('nacionalidad-pais', EmplNacionalidadController::class)->parameters([
        'nacionalidad-pais' => 'nacionalidad'
    ]);
    Route::resource('pais', PaisController::class)->parameters([
        'pais' => 'pais'
    ]);

    Route::resource('empl-discapacidad', EmplTipoDiscapacidadController::class)->parameters([
        'empl-discapacidad' => 'discapacidad'
    ]);

    Route::resource('discapacidad', DiscapacidadController::class)->parameters([
        'discapacidad' => 'discapacidad'
    ]);

    Route::resource('igss-ocupacion', IgssOcupacionController::class)->parameters([
        'igss-ocupacion' => 'ocupacion'
    ]);
    Route::resource('emple-ocupacion', EmpleOcupacionController::class)->parameters([
        'emple-ocupacion' => 'ocupacion'
    ]);
    Route::resource('ocupacion', OcupacionController::class)->parameters([
        'ocupacion' => 'ocupacion'
    ]);
    Route::resource('empl-sexo', EmplSexoController::class)->parameters([
        'empl-sexo' => 'sexo'
    ]);
    Route::resource('genero', GeneroController::class)->parameters([
        'genero' => 'genero'
    ]);
    Route::resource('empl-estado-civil', EmplEstadoCivilController::class)->parameters([
        'empl-estado-civil' => 'estado_civil'
    ]);
    Route::resource('estado-civil', EstadoCivilController::class)->parameters([
        'estado-civil' => 'estado_civil'
    ]);
    Route::resource('empl-jornada-trabajo', EmplJornadaTrabajoController::class)->parameters([
        'empl-jornada-trabajo' => 'jornada_trabajo'
    ]);
    Route::resource('turno', TurnoController::class)->parameters([
        'turno' => 'turno'
    ]);
    Route::resource('empl-doc-identificacion', EmplDocIdentificacionController::class)->parameters([
        'empl-doc-identificacion' => 'doc_identificacion'
    ]);
    Route::resource('empl-temp-contrato', EmplTemporalidadContratoController::class)->parameters([
        'empl-temp-contrato' => 'temporalidad'
    ]);
    Route::resource('tipo-contrato', TipoContratoController::class)->parameters([
        'tipo-contrato' => 'tipo_contrato'
    ]);
    Route::resource('empl-tipo-contrato', EmplTipoContratoController::class)->parameters([
        'empl-tipo-contrato' => 'tipo_contrato'
    ]);
    Route::resource('empl-nivel-educativo', EmplNivelEducativoController::class)->parameters([
        'empl-nivel-educativo' => 'nivel_educativo'
    ]);
    Route::resource('nivel-educativo', NivelEducativoController::class)->parameters([
        'nivel-educativo' => 'nivel_educativo'
    ]);
    Route::resource('empl-pueblo', EmplPuebloPertenenciaController::class)->parameters([
        'empl-pueblo' => 'pueblo'
    ]);
    Route::resource('pueblo', PuebloController::class)->parameters([
        'pueblo' => 'pueblo'
    ]);
    Route::resource('empl-linguistica', EmplComunidadLinguisticaController::class)->parameters([
        'empl-linguistica' => 'linguistica'
    ]);
    Route::resource('comunidad-linguistica', ComunidadLinguisticaController::class)->parameters([
        'comunidad-linguistica' => 'comunidad_linguistica'
    ]);
    Route::resource('igss-tipo-salario', IgssTipoSalarioController::class)->parameters([
        'igss-tipo-salario' => 'tipo_salario'
    ]);
    Route::resource('tipo-salario', TipoSalarioController::class)->parameters([
        'tipo-salario' => 'tipo_salario'
    ]);
    Route::resource('igss-departamento', IgssDepartamentoController::class)->parameters([
        'igss-departamento' => 'departamento'
    ]);
    Route::resource('municipio', MunicipioController::class)->parameters([
        'municipio' => 'municipio'
    ]);
    Route::resource('tipos-planilla', IgssTipoPlanillaController::class)->parameters([
        'tipos-planilla' => 'tipo_planilla'
    ]);
    Route::resource('tipo-pago', TipoPagoController::class)->parameters([
        'tipo-pago' => 'tipo_pago'
    ]);
    Route::resource('moneda', MonedaController::class)->parameters([
        'moneda' => 'moneda'
    ]);
    Route::resource('seguro', SeguroController::class)->parameters([
        'seguro' => 'seguro'
    ]);
    Route::resource('banco', BancoController::class)->parameters([
        'banco' => 'banco'
    ]);
    Route::resource('tipo-cuenta', TipoCuentaController::class)->parameters([
        'tipo-cuenta' => 'tipo_cuenta'
    ]);
    Route::resource('puesto', PuestoController::class)->parameters([
        'puesto' => 'puesto'
    ]);
    Route::resource('centro-costo', CentroCostoController::class)->parameters([
        'centro-costo' => 'centro_costo'
    ]);
    Route::resource('mano-obra', ManoObraController::class)->parameters([
        'mano-obra' => 'mano_obra'
    ]);
    Route::resource('titulo-academico', TituloAcademicoController::class)->parameters([
        'titulo-academico' => 'titulo_academico'
    ]);
    Route::resource('grado-academico', GradoAcademicoController::class)->parameters([
        'grado-academico' => 'grado_academico'
    ]);
    Route::resource('tipo-documento', TipoDocumentoController::class)->parameters([
        'tipo-documento' => 'tipo_documento'
    ]);
    Route::resource('religion', ReligionController::class)->parameters([
        'religion' => 'religion'
    ]);
    Route::resource('tipo-casa', TipoCasaController::class)->parameters([
        'tipo-casa' => 'tipo_casa'
    ]);
    Route::resource('tipo-transporte', TipoTransporteController::class)->parameters([
        'tipo-transporte' => 'tipo_transporte'
    ]);
    Route::resource('tipo-incapacidad', TipoIncapacidadController::class)->parameters([
        'tipo-incapacidad' => 'tipo_incapacidad'
    ]);
    Route::resource('tipo-suspension', TipoSuspensionController::class)->parameters([
        'tipo-suspension' => 'tipo_suspension'
    ]);
    Route::resource('tipo-vacaciones', TipoVacacionesController::class)->parameters([
        'tipo-vacaciones' => 'tipo_vacaciones'
    ]);
    Route::resource('tipo-permiso', TipoPermisoController::class)->parameters([
        'tipo-permiso' => 'tipo_permiso'
    ]);
    Route::resource('profesiones', ProfesionController::class)->parameters([
        'profesiones' => 'profesion'
    ]);

    //Route::resource('profesiones', ProfesionController::class);
    Route::post('cargas/{carga}/enviar-notificaciones', [CargaController::class, 'enviarNotificaciones'])->name('cargas.enviar_notificaciones');
    Route::get('/empresas/{empresa}/reporte-mensual', [App\Http\Controllers\EmpresaController::class, 'reporteMensual'])->name('empresas.reporte_mensual');

    Route::get('envio-masivo', [CargaController::class, 'enviarBoletasMasivas'])->name('cargas.enviarBoletasMasivas');
    

});

require __DIR__.'/auth.php';
