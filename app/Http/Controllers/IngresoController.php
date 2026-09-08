<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Ingreso;
use App\Models\TipoIngreso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngresoController extends Controller
{
    public function index(Empleado $empleado)
    {
        $ingresos = $empleado->ingresos()->with('tipoIngreso')->get();
        return view('ingresos.index', compact('empleado', 'ingresos'));
    }

    public function create(Empleado $empleado)
    {
        $tipos = TipoIngreso::all();
        return view('ingresos.create', compact('empleado', 'tipos'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'tipo_ingreso_id' => 'required|exists:tipo_ingresos,id',
            'descripcion' => 'required|string|max:255',
            'monto_cuota' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
        ]);

        $data = $request->all();
        $data['empleado_id'] = $empleado->id;
        $data['estado'] = $request->has('estado');
        
        $esRecurrente = $request->input('tipo_cobro') === 'recurrente';
        $data['es_recurrente'] = $esRecurrente;

        if ($esRecurrente) {
            $data['monto_total'] = null;
            $data['cantidad_cuotas'] = null;
        } else {
            // Si es un pago único (ej. una comisión de un mes), forzamos a 1 cuota si viene vacío
            if (empty($data['cantidad_cuotas'])) {
                $data['cantidad_cuotas'] = 1;
                $data['monto_total'] = $data['monto_cuota'];
            }
        }

        $ingreso = Ingreso::create($data);

        // Generar las transacciones si NO es recurrente
        if (!$esRecurrente && !empty($data['cantidad_cuotas'])) {
            $fechaProyectada = Carbon::parse($data['fecha_inicio']);
            
            for ($i = 1; $i <= $data['cantidad_cuotas']; $i++) {
                $ingreso->pagosDetalle()->create([
                    'numero_cuota' => $i,
                    'monto' => $data['monto_cuota'],
                    'fecha_pago_proyectada' => $i === 1 ? $fechaProyectada->format('Y-m-d') : $fechaProyectada->addMonth()->format('Y-m-d'),
                ]);
            }
        }

        return redirect()->route('ingresos.index', $empleado->id)
                         ->with('success', 'Ingreso adicional registrado exitosamente.');
    }

    public function edit(Ingreso $ingreso)
    {
        $ingreso->load('empleado');
        $tipos = TipoIngreso::all();
        return view('ingresos.edit', compact('ingreso', 'tipos'));
    }

    public function update(Request $request, Ingreso $ingreso)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $ingreso->update([
            'descripcion' => $request->descripcion,
            'observaciones' => $request->observaciones,
            'estado' => $request->has('estado'),
            'fecha_fin' => $ingreso->es_recurrente ? $request->fecha_fin : $ingreso->fecha_fin,
        ]);

        return redirect()->route('ingresos.index', $ingreso->empleado_id)
                         ->with('success', 'Datos del ingreso actualizados.');
    }

    public function destroy(Ingreso $ingreso)
    {
        $empleado_id = $ingreso->empleado_id;
        $ingreso->delete(); 
        return redirect()->route('ingresos.index', $empleado_id)
                         ->with('success', 'Ingreso eliminado.');
    }
}
