<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\EmpleadoVacacion;
use Illuminate\Http\Request;

class EmpleadoVacacionController extends Controller
{
    public function index(Empleado $empleado)
    {
        // Traemos el historial de vacaciones ordenado desde la más reciente
        $vacaciones = $empleado->vacaciones()->orderBy('fecha_inicio', 'desc')->get();
        
        // Llamamos al "Cerebro Matemático" que configuramos en el modelo
        $calculo = $empleado->calcularSaldoVacaciones();

        return view('empleados.vacaciones', compact('empleado', 'vacaciones', 'calculo'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            // Validamos que acepte números decimales (ej. 0.5, 1.5, 5)
            'dias_tomados' => 'required|numeric|min:0.5',
            'observaciones' => 'nullable|string|max:255'
        ]);

        EmpleadoVacacion::create([
            'empleado_id' => $empleado->id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias_tomados' => $request->dias_tomados,
            'observaciones' => $request->observaciones,
        ]);

        return back()->with('success', 'Registro de vacaciones guardado exitosamente.');
    }

    public function destroy(EmpleadoVacacion $vacacion)
    {
        $vacacion->delete();
        return back()->with('success', 'Registro de vacaciones eliminado.');
    }
}