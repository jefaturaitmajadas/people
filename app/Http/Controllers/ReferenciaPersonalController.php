<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\ReferenciaPersonal;
use Illuminate\Http\Request;

class ReferenciaPersonalController extends Controller
{
    public function index(Empleado $empleado)
    {
        // Traemos las referencias personales del empleado
        $referencias = $empleado->referenciasPersonales()->get();
        return view('referencia_personal.index', compact('empleado', 'referencias'));
    }

    public function create(Empleado $empleado)
    {
        return view('referencia_personal.create', compact('empleado'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:50',
        ]);

        $data = $request->all();
        $data['empleado_id'] = $empleado->id;
        
        ReferenciaPersonal::create($data);
        return redirect()->route('referencia-personal.index', $empleado->id)
                         ->with('success', 'Referencia personal registrada exitosamente.');
    }

    public function edit(ReferenciaPersonal $referencia)
    {
        $referencia->load('empleado');
        return view('referencia_personal.edit', compact('referencia'));
    }

    public function update(Request $request, ReferenciaPersonal $referencia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:50',
        ]);

        $referencia->update($request->all());
        return redirect()->route('referencia-personal.index', $referencia->empleado_id)
                         ->with('success', 'Referencia personal actualizada exitosamente.');
    }

    public function destroy(ReferenciaPersonal $referencia)
    {
        $empleado_id = $referencia->empleado_id;
        $referencia->delete();
        
        return redirect()->route('referencia-personal.index', $empleado_id)
                         ->with('success', 'Referencia personal eliminada.');
    }
}
