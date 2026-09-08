<?php


namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\PrestamoCuota;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    public function index(Empleado $empleado)
    {
        $disponibilidad = $empleado->calcularDisponibilidadPrestamo();
        $prestamos = $empleado->prestamos()->with('cuotas')->orderBy('created_at', 'desc')->get();

        return view('empleados.prestamos.index', compact('empleado', 'disponibilidad', 'prestamos'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $disponibilidad = $empleado->calcularDisponibilidadPrestamo();

        $request->validate([
            'monto_solicitado' => 'required|numeric|min:1|max:' . $disponibilidad['disponible_neto'],
            'frecuencia' => 'required|in:Mensual,Quincenal',
            'cantidad_cuotas' => 'required|integer|min:1|max:' . ($request->frecuencia == 'Mensual' ? 24 : 48),
        ]);

        $montoSolicitado = $request->monto_solicitado;
        $montoAhorroTotal = $montoSolicitado * 0.01;
        $montoTotalPrestamo = $montoSolicitado + $montoAhorroTotal;

        // Crear el préstamo en estado Borrador
        $prestamo = Prestamo::create([
            'empleado_id' => $empleado->id,
            'monto_solicitado' => $montoSolicitado,
            'monto_total' => $montoTotalPrestamo,
            'frecuencia' => $request->frecuencia,
            'cantidad_cuotas' => $request->cantidad_cuotas,
            'estado' => 'Borrador'
        ]);

        // Calcular montos por cuota
        $capitalPorCuota = $montoSolicitado / $request->cantidad_cuotas;
        $ahorroPorCuota = $montoAhorroTotal / $request->cantidad_cuotas;
        $totalPorCuota = $capitalPorCuota + $ahorroPorCuota;

        // Generar las fechas de las cuotas
        $fechaActual = Carbon::now();

        for ($i = 1; $i <= $request->cantidad_cuotas; $i++) {
            
            if ($request->frecuencia == 'Mensual') {
                // Siguiente mes, día 1
                $fechaActual->addMonthNoOverflow()->startOfMonth();
            } else {
                // Quincenal: Alterna entre el 1 y el 16
                if ($fechaActual->day < 16) {
                    $fechaActual->day = 16;
                } else {
                    $fechaActual->addMonthNoOverflow()->startOfMonth();
                }
            }

            PrestamoCuota::create([
                'prestamo_id' => $prestamo->id,
                'numero_cuota' => $i,
                'fecha_aplicacion' => $fechaActual->format('Y-m-d'),
                'monto_capital' => round($capitalPorCuota, 2),
                'monto_ahorro' => round($ahorroPorCuota, 2),
                'monto_total' => round($totalPorCuota, 2),
                'pagado' => false
            ]);
        }

        return back()->with('success', 'Préstamo generado en borrador. Por favor imprima el contrato para firmas.');
    }

    public function contrato(Prestamo $prestamo)
    {
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));
        $empleado = $prestamo->empleado;
        return view('empleados.prestamos.contrato', compact('prestamo', 'empleado', 'empresa'));
    }

    public function aprobar(Prestamo $prestamo)
    {
        $prestamo->update(['estado' => 'Activo']);
        return back()->with('success', 'Préstamo aprobado y activo. Se descontará en las próximas planillas.');
    }

    public function estadoCuenta(Prestamo $prestamo)
    {
        $empleado = $prestamo->empleado;
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));

        // Traemos las cuotas ordenadas
        $cuotas = $prestamo->cuotas()->orderBy('numero_cuota', 'asc')->get();

        // Cálculos para el resumen del estado de cuenta
        $totalAbonado = $cuotas->where('pagado', true)->sum('monto_total');
        $capitalAbonado = $cuotas->where('pagado', true)->sum('monto_capital');
        $saldoPendiente = $prestamo->monto_total - $totalAbonado;

        return view('empleados.prestamos.estado_cuenta', compact(
            'prestamo', 'empleado', 'empresa', 'cuotas', 'totalAbonado', 'capitalAbonado', 'saldoPendiente'
        ));
    }

    public function destroy(Prestamo $prestamo)
    {
        // Medida de seguridad: Solo permitir eliminar si está en Borrador
        if ($prestamo->estado !== 'Borrador') {
            return back()->with('error', 'Solo se pueden eliminar préstamos que estén en estado Borrador.');
        }

        $prestamo->delete();
        
        return back()->with('success', 'El préstamo en borrador ha sido eliminado correctamente.');
    }
}
