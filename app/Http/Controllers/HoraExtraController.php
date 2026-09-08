<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\HoraExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoraExtraController extends Controller
{
    public function index(Empleado $empleado)
    {
        $horas = $empleado->horasExtras()->orderBy('fecha_presentacion', 'desc')->get();
        return view('horas_extras.index', compact('empleado', 'horas'));
    }

    public function create(Empleado $empleado)
    {
        // Asegúrate de que en tu BD de empleados exista el campo 'salario_por_hora', 
        // o calcúlalo al vuelo (ej: salario_base / 30 / 8)
        $salario_hora = $empleado->salario_por_hora ?? 0; 
        
        return view('horas_extras.create', compact('empleado', 'salario_hora'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'fecha_presentacion' => 'required|date',
            'cantidad_horas' => 'required|numeric|min:0.5',
            'salario_por_hora_sugerido' => 'required|numeric|min:0.01',
            'factor_multiplicador' => 'required|numeric'
        ]);

        $data = $request->all();
        $data['empleado_id'] = $empleado->id;
        $data['usuario_creador_id'] = Auth::id(); // Guardamos quién registró esto
        
        // El sistema recalcula el total por seguridad backend (horas * salario * factor)
        $data['total_pagar'] = round($data['cantidad_horas'] * $data['salario_por_hora_sugerido'] * $data['factor_multiplicador'], 2);

        HoraExtra::create($data);

        return redirect()->route('horas-extras.index', $empleado->id)
                         ->with('success', 'Horas extras registradas correctamente.');
    }

    public function destroy(HoraExtra $hora_extra)
    {
        // Solo permitir eliminar si no ha sido pagada en planilla
        if ($hora_extra->pagado) {
            return back()->with('error', 'No se puede eliminar un registro que ya fue pagado en planilla.');
        }

        $empleado_id = $hora_extra->empleado_id;
        $hora_extra->delete();
        
        return redirect()->route('horas-extras.index', $empleado_id)
                         ->with('success', 'Registro de horas extras eliminado.');
    }
}
