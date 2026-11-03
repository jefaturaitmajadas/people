<?php

namespace App\Http\Controllers;

use App\Models\Planilla;
use App\Models\PlanillaDetalle;
use App\Models\Empleado;
use App\Models\HoraExtra;
use App\Models\Incidencia;
use App\Models\Descuento;
use App\Models\DescuentoCuota;
use App\Models\Ingreso;
use App\Models\IngresoPago;
use App\Models\HorasExtra;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\ArchivoBancosExport;
use Maatwebsite\Excel\Facades\Excel;


class PlanillaController extends Controller
{
    public function index()
    {
        $planillas = Planilla::orderBy('created_at', 'desc')->get();
        return view('planillas.index', compact('planillas'));
    }

    public function create()
    {
        return view('planillas.create');
    }

    public function store(Request $request)
    {
        
    
        $request->validate([
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_periodo' => 'required|in:mensual,quincenal,semanal',
        ]);

        

        if($request->tipo_periodo == 'mensual'){
            $tipoPlanilla = 2;
        }

        if($request->tipo_periodo == 'quincenal'){
            $tipoPlanilla = 1;
        }

        try {
            DB::beginTransaction();

            $planilla = Planilla::create([
                'codigo' => 'PLA-' . Carbon::now()->format('YmdHi'),
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_periodo' => $request->tipo_periodo,
                'estado' => 'borrador',
                'usuario_creador_id' => Auth::id(),
            ]);

            $total_nomina = 0;
            //$empleados = Empleado::where('mano_obra_id',$tipoPlanilla)->where('codigo_empleado',124)->get(); 
            $empleados = Empleado::where('mano_obra_id',$tipoPlanilla)->whereNull('fecha_salida')->get(); 

            ///dd(count($empleados));

            foreach ($empleados as $empleado) {
                
                // A. Días Trabajados
                $dias_base = ($request->tipo_periodo == 'quincenal') ? 15 : 30;
                
                $dias_descuento = Incidencia::where('empleado_id', $empleado->id)
                    ->where('aplicado_en_planilla', false)
                    ->whereHas('tipoIncidencia', function($q) {
                        $q->where('descuenta_salario', true);
                    })->sum('cantidad_dias');

                $dias_trabajados = $dias_base - $dias_descuento;

                $base_bono_decreto = 250;
                $proporcional_bono_decreto = ((250)/30 * $dias_trabajados);
                if ($dias_trabajados < 0) $dias_trabajados = 0;

                // B. Salario Proporcional
                $salario_base = $empleado->salario ?? 0; // Asegúrate que tu campo en la BD sea 'salario' o 'salario_base'
                //$salario_proporcional = ($salario_base / $dias_base) * $dias_trabajados;
                $salario_proporcional = ($salario_base / 30) * $dias_trabajados;

               //dd($empleado, $dias_base, $dias_trabajados, $proporcional_bono_decreto, $salario_base, $salario_proporcional);
                // C. Horas Extras
                $total_horas_extras = HoraExtra::where('empleado_id', $empleado->id)
                    ->where('pagado', false)
                    ->sum('total_pagar');

                // D. IGSS Laboral (4.83%)
                $igss_laboral = ($salario_proporcional + $total_horas_extras) * 0.0483;
                
                // ---------------------------------------------------------
                // NUEVO: E. Cálculo de ISR con Exención del Decreto 13-2026
                // ---------------------------------------------------------
                $isr_descuento = 0;
                
                // Define aquí el salario mínimo vigente para el año en curso
                $salario_minimo_vigente = 4002.28; // Ajusta según sector no agrícola u otro
                
                // Solo se calcula ISR si el salario base es MAYOR al mínimo legal
                if ($salario_base > $salario_minimo_vigente) {
                    
                    $mesPlanilla = Carbon::parse($request->fecha_inicio)->month;
                    $mesProyectado = 12 - $mesPlanilla;
                    
                    //Historico
                    $salarioAnteriores = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('salario_base_mes');
                    
                        

                    $salariosProyectados       = ($salario_base * $mesProyectado) + $salario_base;
                    $ingreso_anual_proyectado  = ($salarioAnteriores+$salariosProyectados);

                    $historicoHorasExtras = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('total_horas_extras');

                    $horasExtrasActuales = HoraExtra::where('empleado_id',$empleado->id)
                        ->where('pagado',0)
                        ->sum('total_pagar');
                    
                    $horaExtraProyectado = $historicoHorasExtras + $horasExtrasActuales;

                     
                    $historicoIngresosAdicionales = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('total_ingresos_adicionales');
                    
                    $ingresosAdicionalesActuales = Ingreso::where('empleado_id',$empleado->id)
                        ->where('estado',1)
                        ->sum('monto_total');
                    
                    $ingreosAdicionalesProyectado = $historicoIngresosAdicionales + $ingresosAdicionalesActuales;

                    
                    $historicoBonoDecreto = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('bono_decreto');

                    $bonoDecretoActuales = ($base_bono_decreto * $mesProyectado) + $base_bono_decreto;

                    $bonoDecretoProyectado = $historicoBonoDecreto + $bonoDecretoActuales;

                    $totalIngresos = $ingreso_anual_proyectado + $horaExtraProyectado + $bonoDecretoProyectado + $ingreosAdicionalesProyectado;

                    

                    // 2. Deducciones Legales (Art. 72 Ley de Actualización Tributaria)
                    $gastos_personales = 54051.36; // Deducción fija
                    //$igss_anual = $ingreso_anual_proyectado * 0.0483;

                    $historicoIgss = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('igss_laboral');
                    
                    $igssFuturo       = (($salario_base * 0.0483) * ($mesProyectado + 1));
                    $igss_anual = $historicoIgss + $igssFuturo;
                    /* if($empleado->id == 2){
                            dd($historicoIgss, $igssFuturo, $igss_anual, $mesProyectado);
                        }*/
                    
                    


                    

                    $historicoISR = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('isr');
                    
                    

                    // 1. Convertimos las fechas a objetos Carbon para poder calcular
                    $fechaAlta = \Carbon\Carbon::parse($empleado->fecha_inicio);

                    $añoActual = date('Y');
                    $inicioPeriodo = \Carbon\Carbon::parse(($añoActual - 1) . '-12-01'); // 1 de diciembre del año pasado
                    $finPeriodo = \Carbon\Carbon::parse($añoActual . '-11-30');          // 30 de noviembre de este año

                    // 2. Determinamos cuántos días aplican para este pago
                    if ($fechaAlta->lt($inicioPeriodo)) {
                        // Si entró antes de que empezara el período actual, tiene el año completo (100%)
                        $diferenciaDias = 365;
                    } else {
                        // Si entró durante el período actual, calculamos los días exactos
                        // Le sumamos 1 para ser inclusivos (ej: si empezó el 30 de nov, trabajó 1 día, no 0)
                        $diferenciaDias = $fechaAlta->diffInDays($finPeriodo) + 1;
                    }

                    

                    // Por seguridad, evitamos que exceda los 365 días en años bisiestos
                    $diferenciaDias = min($diferenciaDias, 365);

                    // 3. Cálculo final
                    $preAguinaldo = ($salario_base / 365) * $diferenciaDias;
                        
                    
                    // El período legal del Bono 14 es del 1 de julio al 30 de junio
                    $inicioPeriodoBono = \Carbon\Carbon::parse(($añoActual - 1) . '-07-01');
                    $finPeriodoBono = \Carbon\Carbon::parse($añoActual . '-06-30'); 

                    // 2. Determinamos cuántos días aplican para el Bono 14
                    if ($fechaAlta->lt($inicioPeriodoBono)) {
                        // Si entró antes del 1 de julio del año pasado, tiene el año completo (100%)
                        $diferenciaDiasBono = 365;
                    } else {
                        // Si entró después del 1 de julio, calculamos los días exactos hasta el 30 de junio
                        // Sumamos 1 para que el día de ingreso cuente como trabajado
                        $diferenciaDiasBono = $fechaAlta->diffInDays($finPeriodoBono) + 1;
                    }

                    // Límite de seguridad para que no exceda 365 días
                    $diferenciaDiasBono = min($diferenciaDiasBono, 365);

                    // 3. Cálculo final del Bono 14
                    $preBono14 = ($salario_base / 365) * $diferenciaDiasBono;
                    $ingresosTotalesMasBonos = $totalIngresos + $preBono14 + $preAguinaldo;

                    
                    $descuentosTotales = $igss_anual + $preBono14 + $preAguinaldo + $gastos_personales;

                   


                    // 3. Renta Imponible
                    $rentaImponible = $ingresosTotalesMasBonos - $descuentosTotales;

                    
                   
                    if ($rentaImponible > 0) {
                        $impuesto_anual = 0;
                        
                        // 4. Tramos de ISR
                        if ($rentaImponible <= 300000) {
                            $impuesto_anual = $rentaImponible * 0.05;
                        } else {
                            $impuesto_anual = 15000 + (($rentaImponible - 300000) * 0.07);
                        }

                        
                        
                        $impuesto_anual = ($impuesto_anual - ( $historicoISR ));
                        //dd($impuesto_anual);
                        // 5. Fraccionamiento Mensual/Quincenal
                        $isr_mensual = $impuesto_anual / ($mesProyectado + 1); // *** aca es los meses que faltan
                        
                        if ($request->tipo_periodo == 'quincenal') {
                            $isr_descuento = $isr_mensual / 2;
                        } elseif ($request->tipo_periodo == 'semanal') {
                            $isr_descuento = $isr_mensual / 4;
                        } else {
                            $isr_descuento = $isr_mensual;
                        }
                    }
                }

                //dd($isr_descuento);
                // F. Ingresos Adicionales
                $total_ingresos = 0;
                $total_ingresos += IngresoPago::whereHas('ingreso', function($q) use ($empleado) {
                    $q->where('empleado_id', $empleado->id);
                })->where('pagado', false)->where('fecha_pago_proyectada', '<=', $request->fecha_fin)->sum('monto'); //los 250 es por los meses que recibira en el año
                
                $total_ingresos += Ingreso::where('empleado_id', $empleado->id)
                    ->where('es_recurrente', true)
                    ->where('estado', true)->sum('monto_cuota');

                // G. Descuentos (Ahora incluimos el ISR calculado)
                $total_descuentos = 0;

                //$isr_descuento =0; //984.6639
                
                // Sumamos el ISR al bloque de descuentos
                $total_descuentos += $isr_descuento;
                
                // --- NUEVO: CÁLCULO DE PRÉSTAMOS ---
                $descuento_prestamos = \App\Models\PrestamoCuota::whereHas('prestamo', function($q) use ($empleado) {
                        $q->where('empleado_id', $empleado->id)
                          ->where('estado', 'Activo');
                    })
                    ->where('pagado', false)
                    ->where('fecha_aplicacion', '<=', $request->fecha_fin)
                    ->sum('monto_total');
                
                // Lo sumamos al total de descuentos, pero OJO: se sumó DESPUÉS del cálculo del ISR e IGSS
                $total_descuentos += $descuento_prestamos;

                $total_descuentos += DescuentoCuota::whereHas('descuento', function($q) use ($empleado) {
                    $q->where('empleado_id', $empleado->id);
                })->where('pagado', false)->where('fecha_cobro_proyectada', '<=', $request->fecha_fin)->sum('monto');
                
                $total_descuentos += Descuento::where('empleado_id', $empleado->id)
                    ->whereNull('monto_total')
                    ->where('estado', true)->sum('cuota');
                
                //dd($total_descuentos);

                // H. Líquido a Recibir
                $liquido = $salario_proporcional + $proporcional_bono_decreto +$total_horas_extras + $total_ingresos - $igss_laboral - $total_descuentos;

                //dd($salario_proporcional , $total_horas_extras , $total_ingresos , $igss_laboral , $total_descuentos);
                    
              //  dd($salario_proporcional , $total_horas_extras , $total_ingresos , $igss_laboral , $total_descuentos);

                // 3. Guardar el Recibo del Empleado
                PlanillaDetalle::create([
                    'planilla_id' => $planilla->id,
                    'empleado_id' => $empleado->id,
                    'dias_trabajados' => $dias_trabajados,
                    'salario_base_mes' => $salario_base,
                    'salario_proporcional' => $salario_proporcional,
                    'total_horas_extras' => $total_horas_extras,
                    'total_ingresos_adicionales' => $total_ingresos,
                    'igss_laboral' => $igss_laboral,
                    'isr' => $isr_descuento, // <--- GUARDAMOS EL ISR AQUI
                    'pago_prestamos' => $descuento_prestamos,
                    'total_descuentos' => $total_descuentos, // Este total ya incluye el ISR sumado
                    'liquido_a_recibir' => $liquido,
                    'bono_decreto' => $proporcional_bono_decreto
                ]);

                $total_nomina += $liquido;
            }

            $planilla->update(['total_nomina' => $total_nomina]);
            DB::commit();

            return redirect()->route('planillas.show', $planilla->id)
                             ->with('success', 'Planilla generada en BORRADOR exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al calcular planilla: ' . $e->getMessage());
        }
    }

    /*public function storeOriginal(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_periodo' => 'required|in:mensual,quincenal,semanal',
        ]);

        try {
            DB::beginTransaction();

            $planilla = Planilla::create([
                'codigo' => 'PLA-' . Carbon::now()->format('YmdHi'),
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_periodo' => $request->tipo_periodo,
                'estado' => 'borrador',
                'usuario_creador_id' => Auth::id(),
            ]);

            $total_nomina = 0;
            $empleados = Empleado::all(); 

            foreach ($empleados as $empleado) {
                
                // A. Días Trabajados
                $dias_base = ($request->tipo_periodo == 'quincenal') ? 15 : 30;
                
                $dias_descuento = Incidencia::where('empleado_id', $empleado->id)
                    ->where('aplicado_en_planilla', false)
                    ->whereHas('tipoIncidencia', function($q) {
                        $q->where('descuenta_salario', true);
                    })->sum('cantidad_dias');

                $dias_trabajados = $dias_base - $dias_descuento;
                if ($dias_trabajados < 0) $dias_trabajados = 0;

                // B. Salario Proporcional
                $salario_base = $empleado->salario ?? 0; // Asegúrate que tu campo en la BD sea 'salario' o 'salario_base'
                $salario_proporcional = ($salario_base / $dias_base) * $dias_trabajados;

                // C. Horas Extras
                $total_horas_extras = HoraExtra::where('empleado_id', $empleado->id)
                    ->where('pagado', false)
                    ->sum('total_pagar');

                // D. IGSS Laboral (4.83%)
                $igss_laboral = ($salario_proporcional + $total_horas_extras) * 0.0483;

                // ---------------------------------------------------------
                // NUEVO: E. Cálculo de ISR con Exención del Decreto 13-2026
                // ---------------------------------------------------------
                $isr_descuento = 0;
                
                // Define aquí el salario mínimo vigente para el año en curso
                $salario_minimo_vigente = 4002.28; // Ajusta según sector no agrícola u otro
                
                // Solo se calcula ISR si el salario base es MAYOR al mínimo legal
                if ($salario_base > $salario_minimo_vigente) {
                    
                    $mesPlanilla = Carbon::parse($request->fecha_inicio)->month;
                    $mesProyectado = 12 - $mesPlanilla;

                    //Historico
                    $salarioAnteriores = PlanillaDetalle::
                        join('planillas','planillas.id','planilla_detalles.planilla_id')
                        ->where('planilla_detalles.empleado_id',$empleado->id)
                        ->where('planillas.estado','cerrada')
                        ->sum('salario_base_mes');

                    $salariosProyectados = ($salario_base * $mesProyectado) + $salario_base;
                    $salarioTotal        = ($salarioAnteriores+$salariosProyectados);
                    
                    // 1. Proyección Anual
                    $ingreso_anual_proyectado = $salario_base * 12;
                    
                    // 2. Deducciones Legales (Art. 72 Ley de Actualización Tributaria)
                    $gastos_personales = 54051.36; // Deducción fija
                    $igss_anual = $ingreso_anual_proyectado * 0.0483;
                    
                    // 3. Renta Imponible
                    $renta_neta = $ingreso_anual_proyectado - $gastos_personales - $igss_anual;

                    if ($renta_neta > 0) {
                        $impuesto_anual = 0;
                        
                        // 4. Tramos de ISR
                        if ($renta_neta <= 300000) {
                            $impuesto_anual = $renta_neta * 0.05;
                        } else {
                            $impuesto_anual = 15000 + (($renta_neta - 300000) * 0.07);
                        }

                        // 5. Fraccionamiento Mensual/Quincenal
                        $isr_mensual = $impuesto_anual / 5; // *** aca es los meses que faltan
                        
                        if ($request->tipo_periodo == 'quincenal') {
                            $isr_descuento = $isr_mensual / 2;
                        } elseif ($request->tipo_periodo == 'semanal') {
                            $isr_descuento = $isr_mensual / 4;
                        } else {
                            $isr_descuento = $isr_mensual;
                        }
                    }
                }

                // F. Ingresos Adicionales
                $total_ingresos = 0;
                $total_ingresos += IngresoPago::whereHas('ingreso', function($q) use ($empleado) {
                    $q->where('empleado_id', $empleado->id);
                })->where('pagado', false)->where('fecha_pago_proyectada', '<=', $request->fecha_fin)->sum('monto'); //los 250 es por los meses que recibira en el año
                
                $total_ingresos += Ingreso::where('empleado_id', $empleado->id)
                    ->where('es_recurrente', true)
                    ->where('estado', true)->sum('monto_cuota');

                // G. Descuentos (Ahora incluimos el ISR calculado)
                $total_descuentos = 0;
                
                // Sumamos el ISR al bloque de descuentos
                $total_descuentos += $isr_descuento; 

                $total_descuentos += DescuentoCuota::whereHas('descuento', function($q) use ($empleado) {
                    $q->where('empleado_id', $empleado->id);
                })->where('pagado', false)->where('fecha_cobro_proyectada', '<=', $request->fecha_fin)->sum('monto');
                
                $total_descuentos += Descuento::where('empleado_id', $empleado->id)
                    ->whereNull('monto_total')
                    ->where('estado', true)->sum('cuota');

                // H. Líquido a Recibir
                $liquido = $salario_proporcional + $total_horas_extras + $total_ingresos - $igss_laboral - $total_descuentos;

                // 3. Guardar el Recibo del Empleado
                PlanillaDetalle::create([
                    'planilla_id' => $planilla->id,
                    'empleado_id' => $empleado->id,
                    'dias_trabajados' => $dias_trabajados,
                    'salario_base_mes' => $salario_base,
                    'salario_proporcional' => $salario_proporcional,
                    'total_horas_extras' => $total_horas_extras,
                    'total_ingresos_adicionales' => $total_ingresos,
                    'igss_laboral' => $igss_laboral,
                    'isr' => $isr_descuento, // <--- GUARDAMOS EL ISR AQUI
                    'total_descuentos' => $total_descuentos, // Este total ya incluye el ISR sumado
                    'liquido_a_recibir' => $liquido
                ]);

                $total_nomina += $liquido;
            }

            $planilla->update(['total_nomina' => $total_nomina]);
            DB::commit();

            return redirect()->route('planillas.show', $planilla->id)
                             ->with('success', 'Planilla generada en BORRADOR exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al calcular planilla: ' . $e->getMessage());
        }
    }*/

    public function show(Planilla $planilla)
    {
        $planilla->load('detalles.empleado');
        return view('planillas.show', compact('planilla'));
    }

    public function destroy(Planilla $planilla)
    {
        if ($planilla->estado == 'cerrada') {
            return back()->with('error', 'No puedes eliminar una planilla cerrada.');
        }

        $planilla->delete();
        return redirect()->route('planillas.index')->with('success', 'Planilla borrador eliminada.');
    }

    public function cerrarPlanilla(Planilla $planilla)
    {
        if ($planilla->estado == 'cerrada') {
            return back()->with('error', 'La planilla ya se encuentra cerrada.');
        }

        DB::beginTransaction();
        //try {
            // 1. Cambiamos el estado de la planilla
            $planilla->update(['estado' => 'cerrada']);

            // 2. Procesamos todos los detalles de la planilla
            foreach ($planilla->detalles as $detalle) {
                
                // --- CIERRE DE PRÉSTAMOS ---
                if ($detalle->pago_prestamos > 0) {
                    
                    $cuotasPendientes = \App\Models\PrestamoCuota::whereHas('prestamo', function($q) use ($detalle) {
                            $q->where('empleado_id', $detalle->empleado_id)
                              ->where('estado', 'Activo');
                        })
                        ->where('pagado', false)
                        ->where('fecha_aplicacion', '<=', $planilla->fecha_fin)
                        ->get();

                    foreach ($cuotasPendientes as $cuota) {
                        $cuota->update([
                            'pagado' => true,
                            'fecha_pagado' => \Carbon\Carbon::now(),
                            'planilla_id' => $planilla->id
                        ]);

                        $prestamo = $cuota->prestamo;
                        $cuotasRestantes = $prestamo->cuotas()->where('pagado', false)->count();
                        
                        if ($cuotasRestantes == 0) {
                            $prestamo->update(['estado' => 'Pagado']);
                        }
                    }
                }

                // --- CIERRE DE HORAS EXTRAS ---
                if ($detalle->total_horas_extras > 0) {
                    
                    $horasExtras = \App\Models\HoraExtra::where('empleado_id', $detalle->empleado_id)
                        ->where('pagado', false)
                        ->get();

                    foreach ($horasExtras as $horaExtra) {
                        $horaExtra->update([
                            'pagado' => true,
                            'fecha_pagada' => \Carbon\Carbon::now(),
                            'usuario_confirma_id' => \Illuminate\Support\Facades\Auth::id()
                        ]);
                    }
                }

                // --- CIERRE DE DESCUENTOS EN CUOTAS ---
                // Buscamos las cuotas de descuentos que se cobraron en este período
                $descuentosCuotas = \App\Models\DescuentoCuota::whereHas('descuento', function($q) use ($detalle) {
                        $q->where('empleado_id', $detalle->empleado_id);
                    })
                    ->where('pagado', false)
                    ->where('fecha_cobro_proyectada', '<=', $planilla->fecha_fin)
                    ->get();

                foreach ($descuentosCuotas as $cuotaDescuento) {
                    $cuotaDescuento->update([
                        'pagado' => true,
                        // Si tienes un campo de fecha de pago en esta tabla, puedes descomentar la siguiente línea:
                        // 'fecha_pago' => \Carbon\Carbon::now()
                    ]);
                }
                
                // --- CIERRE DE INGRESOS ADICIONALES EN CUOTAS (Opcional/Recomendado) ---
                $ingresosPagos = \App\Models\IngresoPago::whereHas('ingreso', function($q) use ($detalle) {
                        $q->where('empleado_id', $detalle->empleado_id);
                    })
                    ->where('pagado', false)
                    ->where('fecha_pago_proyectada', '<=', $planilla->fecha_fin)
                    ->get();

                foreach ($ingresosPagos as $pagoIngreso) {
                    $pagoIngreso->update([
                        'pagado' => true,
                    ]);
                }

            }

            DB::commit();
            return back()->with('success', 'Planilla cerrada exitosamente. Los abonos a préstamos, horas extras y descuentos han sido aplicados.');

        /*} catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cerrar la planilla: ' . $e->getMessage());
        }*/
    }

    public function recibo(PlanillaDetalle $detalle)
    {
        $detalle->load(['planilla', 'empleado']);
        return view('planillas.recibo', compact('detalle'));
    }

    public function exportarBancos(Planilla $planilla)
    {
        $cuentaEmpresa = \App\Models\EmpresaCuenta::first(); 

        if (!$cuentaEmpresa) {
            return back()->with('error', 'Debes configurar una Cuenta Bancaria para esta empresa antes de generar el archivo.');
        }

        $fileName = 'Bancos_Planilla_' . $planilla->codigo . '.xls';

        // Usamos la clase \Excel de la librería instalada
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ArchivoBancosExport($planilla, $cuentaEmpresa), 
            $fileName,
            \Maatwebsite\Excel\Excel::XLS // Formato estricto XLS
        );
    }

    public function exportarIgss(Planilla $planilla)
    {
        $empresa = $planilla->empresa;
        $detalles = $planilla->detalles()->with('empleado')->get();

        // 1. Formatear variables del encabezado
        $fechaGeneracion = now()->format('d/m/Y');
        $numeroPatronal  = $empresa->numero_patronal ?? '';
        $mes             = \Carbon\Carbon::parse($planilla->fecha_inicio)->format('m');
        $anio            = \Carbon\Carbon::parse($planilla->fecha_inicio)->format('Y');
        $nombreEmpresa   = $empresa->nombre ?? '';
        $nitEmpresa      = str_replace('-', '', $empresa->nit ?? ''); // Sin guiones
        $correo          = $empresa->correo ?? '';
        
        $razonSocial = mb_strtoupper($empresa->razon_social ?? $empresa->nombre, 'UTF-8');
        $direccion   = mb_strtoupper($empresa->direccion ?? '', 'UTF-8');
        $telefono    = $empresa->telefono ?? '';
        
        $fechaInicio = \Carbon\Carbon::parse($planilla->fecha_inicio)->format('d/m/Y');
        $fechaFin    = \Carbon\Carbon::parse($planilla->fecha_fin)->format('d/m/Y');

        // 2. Construir el contenido del archivo TXT
        $contenido = "";
        
        // Encabezado principal
        $contenido .= "2.2.0|{$fechaGeneracion}|{$numeroPatronal}|{$mes}|{$anio}|{$nombreEmpresa}|{$nitEmpresa}||{$correo}|0\r\n";
        
        // Centros de Trabajo
        $contenido .= "[centros]\r\n";
        $contenido .= "1|{$razonSocial}|{$direccion}|11|{$telefono}||||1|1|701000|\r\n";
        
        // Tipos de planilla
        $contenido .= "[tiposplanilla]\r\n";
        $contenido .= "1|PLANILLA MENSUAL " . mb_strtoupper($nombreEmpresa, 'UTF-8') . "|C|M|1|701000|N|TC|\r\n";
        
        // Liquidaciones
        $contenido .= "[liquidaciones]\r\n";
        $contenido .= "1|1|{$fechaInicio}|{$fechaFin}|O||\r\n";
        
        // Empleados (El Detalle)
        $contenido .= "[empleados]\r\n";
        
        foreach ($detalles as $detalle) {
            $empleado = $detalle->empleado;
            
            $numeroAfiliacion = $empleado->no_igss ?? ''; 
            $primerNombre     = mb_strtoupper($empleado->primer_nombre, 'UTF-8') ?? '';
            $segundoNombre    = mb_strtoupper($empleado->segundo_nombre, 'UTF-8') ?? ''; 
            $primerApellido   = mb_strtoupper($empleado->primer_apellido, 'UTF-8') ?? ''; 
            $segundoApellido  = mb_strtoupper($empleado->segundo_apellido, 'UTF-8') ?? ''; 
            $casadaApellido   = mb_strtoupper($empleado->casada_apellido, 'UTF-8') ?? ''; 
            
            // IGSS suele pedir el salario sujeto a retención (salario base + comisiones) no el líquido.
            //$sueldo           = $empleado->salario ?? 0; //LE SUMO HORAS EXTRAS Y COMISIONES
            $sueldo           = $detalle->salario_base_mes + $detalle->total_horas_extras + $detalle->total_ingresos_adicionales ?? 0; //LE SUMO HORAS EXTRAS Y COMISIONES
            // Las fechas de alta/baja solo deben ir si ocurrieron en ESTE mes, de lo contrario se envían vacías.
            // Para mantener tu formato exacto, las parseamos si existen:
            $fechaAlta = $empleado->fecha_inicio ? \Carbon\Carbon::parse($empleado->fecha_inicio)->format('d/m/Y') : '';
            $fechaBaja = $empleado->fecha_salida ? \Carbon\Carbon::parse($empleado->fecha_salida)->format('d/m/Y') : '';
            
            $nit         = str_replace('-', '', $empleado->nit ?? ''); 
            $ocupacion   = $empleado->ocupacion->igssOcupacion->grupo_primario ?? ''; 
            $salarioTipo = $empleado->tipoSalario->igssTipoSalario->codigo ?? '1'; 
            $dias        = $detalle->dias_laborados ?? 30; 
            
            // Concatenación exacta con los pipes
            $contenido .= "1|{$numeroAfiliacion}|{$primerNombre}|{$segundoNombre}|{$primerApellido}|{$segundoApellido}|{$casadaApellido}|{$sueldo}|{$fechaAlta}|{$fechaBaja}|1|{$nit}|{$ocupacion}|P||{$salarioTipo}||TC|{$dias}|\r\n";
        }
        
        // Suspendidos y Licencias (Se dejan los encabezados listos)
        $contenido .= "[suspendidos]\r\n";
        // Aquí puedes agregar un foreach futuro si manejas suspensiones del IGSS
        
        $contenido .= "[licencias]\r\n";
        
        // Juramento y Cierre
        $contenido .= "[juramento]\r\n";
        $contenido .= "BAJO MI EXCLUSIVA Y ABSOLUTA RESPONSABILIDAD, DECLARO QUE LA INFORMACION QUE AQUI CONSIGNO ES FIEL Y EXACTA, QUE ESTA PLANILLA INCLUYE A TODOS LOS TRABAJADORES QUE ESTUVIERON A MI SERVICIO Y QUE SUS SALARIOS SON LOS EFECTIVAMENTE DEVENGADOS, DURANTE EL MES ARRIBA INDICADO\r\n";
        $contenido .= "[finplanilla]\r\n";

        // 3. Forzar la descarga del archivo como .txt
        $fileName = 'Planilla_IGSS_' . $planilla->codigo . '.txt';

        return response($contenido)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}