<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use App\Models\DescuentoCuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DescuentoCuotaController extends Controller
{
    public function index(Descuento $descuento)
    {
        // Cargamos el descuento con su empleado para mostrar sus datos en el encabezado
        $descuento->load('empleado', 'tipoDescuento');
        
        // Traemos todas las cuotas ordenadas por el número de cuota
        $cuotas = $descuento->cuotasDetalle()->orderBy('numero_cuota', 'asc')->get();
        
        return view('descuento_cuotas.index', compact('descuento', 'cuotas'));
    }

    // Función para procesar un pago manual (ej. el empleado trae efectivo a caja)
    public function pagarManual(Request $request, DescuentoCuota $cuota)
    {
        // Verificamos que no esté pagada ya
        if ($cuota->pagado) {
            return back()->with('error', 'Esta cuota ya se encuentra pagada.');
        }

        // 1. Marcamos la cuota como pagada
        $cuota->update([
            'pagado' => true,
            'fecha_pago' => now(), // Fecha y hora actual
            'usuario_confirma_id' => Auth::id() // Usuario de RRHH/Caja en sesión
        ]);

        // 2. Reducimos el saldo del descuento maestro
        $descuento = $cuota->descuento;
        if ($descuento->saldo !== null) {
            $nuevoSaldo = $descuento->saldo - $cuota->monto;
            
            // Si el saldo llega a 0, desactivamos el descuento automáticamente
            $estado = $nuevoSaldo <= 0 ? false : $descuento->estado;
            
            $descuento->update([
                'saldo' => $nuevoSaldo < 0 ? 0 : $nuevoSaldo,
                'estado' => $estado
            ]);
        }

        return back()->with('success', 'Cuota marcada como pagada manualmente y saldo actualizado.');
    }
}
