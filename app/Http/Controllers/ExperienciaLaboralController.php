<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\ExperienciaLaboral;
use Illuminate\Http\Request;

class ExperienciaLaboralController extends Controller
{
    public function index(Empleado $empleado)
    {
        // Traemos las experiencias del empleado específico
        $experiencias = $empleado->experienciasLaborales()->get();
        return view('experiencia_laboral.index', compact('empleado', 'experiencias'));
    }

    public function create(Empleado $empleado)
    {
        return view('experiencia_laboral.create', compact('empleado'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $data = $request->all();
        $data['empleado_id'] = $empleado->id; // Forzamos el ID del empleado
        
        ExperienciaLaboral::create($data);
        return redirect()->route('experiencia-laboral.index', $empleado->id)
                         ->with('success', 'Experiencia laboral registrada exitosamente.');
    }

    public function edit(ExperienciaLaboral $experiencia)
    {
        // Cargamos la relación del empleado para saber a quién pertenece
        $experiencia->load('empleado');
        return view('experiencia_laboral.edit', compact('experiencia'));
    }

    public function update(Request $request, ExperienciaLaboral $experiencia)
    {
        $experiencia->update($request->all());
        return redirect()->route('experiencia-laboral.index', $experiencia->empleado_id)
                         ->with('success', 'Experiencia laboral actualizada exitosamente.');
    }

    public function destroy(ExperienciaLaboral $experiencia)
    {
        $empleado_id = $experiencia->empleado_id;
        $experiencia->delete();
        return redirect()->route('experiencia-laboral.index', $empleado_id)
                         ->with('success', 'Experiencia laboral eliminada.');
    }
}
