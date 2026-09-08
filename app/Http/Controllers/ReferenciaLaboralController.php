<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\ReferenciaLaboral;
use Illuminate\Http\Request;

class ReferenciaLaboralController extends Controller
{
    public function index(Empleado $empleado)
    {
        // Traemos las referencias laborales del empleado
        $referencias = $empleado->referenciasLaborales()->get();
        return view('referencia_laboral.index', compact('empleado', 'referencias'));
    }

    public function create(Empleado $empleado)
    {
        return view('referencia_laboral.create', compact('empleado'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
        ]);

        $data = $request->all();
        $data['empleado_id'] = $empleado->id;
        
        ReferenciaLaboral::create($data);
        return redirect()->route('referencia-laboral.index', $empleado->id)
                         ->with('success', 'Referencia laboral registrada exitosamente.');
    }

    public function edit(ReferenciaLaboral $referencia)
    {
        $referencia->load('empleado');
        return view('referencia_laboral.edit', compact('referencia'));
    }

    public function update(Request $request, ReferenciaLaboral $referencia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
        ]);

        $referencia->update($request->all());
        return redirect()->route('referencia-laboral.index', $referencia->empleado_id)
                         ->with('success', 'Referencia laboral actualizada exitosamente.');
    }

    public function destroy(ReferenciaLaboral $referencia)
    {
        $empleado_id = $referencia->empleado_id;
        $referencia->delete();
        
        return redirect()->route('referencia-laboral.index', $empleado_id)
                         ->with('success', 'Referencia laboral eliminada.');
    }
}
