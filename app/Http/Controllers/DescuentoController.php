<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Descuento;
use App\Models\TipoDescuento;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DescuentoController extends Controller
{
    public function index(Empleado $empleado)
    {
        $descuentos = $empleado->descuentos()->with('tipoDescuento')->get();
        return view('descuentos.index', compact('empleado', 'descuentos'));
    }

    public function create(Empleado $empleado)
    {
        $tipos = TipoDescuento::all();
        return view('descuentos.create', compact('empleado', 'tipos'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'tipo_descuento_id' => 'required|exists:tipo_descuentos,id',
            'descripcion' => 'required|string|max:255',
            'cuota' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
        ]);

        $data = $request->all();
        $data['empleado_id'] = $empleado->id;
        $data['estado'] = $request->has('estado');
        
        // Determinar si es préstamo o recurrente basado en el radio button de la vista
        $esRecurrente = $request->input('tipo_cobro') === 'recurrente';
        $data['es_recurrente'] = $esRecurrente;

        if (!$esRecurrente) {
            // Es préstamo: El saldo inicial es el monto total
            $data['saldo'] = $data['monto_total'];
        } else {
            // Es recurrente: Limpiamos datos que no aplican
            $data['monto_total'] = null;
            $data['cantidad_cuotas'] = null;
            $data['saldo'] = null;
        }

        // 1. Guardar el descuento maestro
        $descuento = Descuento::create($data);

        // 2. Generar Tabla de Amortización si es un Préstamo/Anticipo
        if (!$esRecurrente && !empty($data['cantidad_cuotas'])) {
            $fechaProyectada = Carbon::parse($data['fecha_inicio']);
            
            for ($i = 1; $i <= $data['cantidad_cuotas']; $i++) {
                $descuento->cuotasDetalle()->create([
                    'numero_cuota' => $i,
                    'monto' => $data['cuota'],
                    // Calcula el mes correspondiente (Si tu nómina es quincenal, deberás cambiar addMonth() por addDays(15))
                    'fecha_cobro_proyectada' => $i === 1 ? $fechaProyectada->format('Y-m-d') : $fechaProyectada->addMonth()->format('Y-m-d'),
                ]);
            }
        }

        return redirect()->route('descuentos.index', $empleado->id)
                         ->with('success', 'Descuento registrado y proyectado exitosamente.');
    }

    public function edit(Descuento $descuento)
    {
        $descuento->load('empleado');
        $tipos = TipoDescuento::all();
        return view('descuentos.edit', compact('descuento', 'tipos'));
    }

    public function update(Request $request, Descuento $descuento)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        // En la edición, para mantener la integridad contable, 
        // solo permitimos actualizar la descripción, las observaciones y si está activo/inactivo.
        // El monto y cuotas ya están en la tabla de amortización.
        
        $descuento->update([
            'descripcion' => $request->descripcion,
            'observaciones' => $request->observaciones,
            'estado' => $request->has('estado'),
            // Solo permitimos editar fecha_fin si es recurrente
            'fecha_fin' => $descuento->es_recurrente ? $request->fecha_fin : $descuento->fecha_fin,
        ]);

        return redirect()->route('descuentos.index', $descuento->empleado_id)
                         ->with('success', 'Datos del descuento actualizados. (Los montos financieros no se alteraron por seguridad contable).');
    }

    public function destroy(Descuento $descuento)
    {
        $empleado_id = $descuento->empleado_id;
        $descuento->delete(); // Eliminará las cuotas en cascada gracias al onDelete('cascade') de la migración
        return redirect()->route('descuentos.index', $empleado_id)
                         ->with('success', 'Descuento y su tabla de amortización eliminados.');
    }
}