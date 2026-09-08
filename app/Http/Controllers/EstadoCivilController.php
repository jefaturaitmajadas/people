<?php

namespace App\Http\Controllers;

use App\Models\EstadoCivil;
use App\Models\EmplEstadoCivil;
use Illuminate\Http\Request;

class EstadoCivilController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $estados = EstadoCivil::with('emplEstadoCivil')->get();
        return view('estado_civil.index', compact('estados'));
    }

    public function create()
    {
        $emplEstados = EmplEstadoCivil::all();
        return view('estado_civil.create', compact('emplEstados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_estado_civil' => 'required|exists:empl_estado_civil,id',
        ]);

        EstadoCivil::create($request->all());
        return redirect()->route('estado-civil.index')->with('success', 'Estado civil del sistema registrado exitosamente.');
    }

    public function edit(EstadoCivil $estado_civil)
    {
        $emplEstados = EmplEstadoCivil::all();
        return view('estado_civil.edit', compact('estado_civil', 'emplEstados'));
    }

    public function update(Request $request, EstadoCivil $estado_civil)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_estado_civil' => 'required|exists:empl_estado_civil,id',
        ]);

        $estado_civil->update($request->all());
        return redirect()->route('estado-civil.index')->with('success', 'Estado civil del sistema actualizado exitosamente.');
    }

    public function destroy(EstadoCivil $estado_civil)
    {
        $estado_civil->delete();
        return redirect()->route('estado-civil.index')->with('success', 'Estado civil eliminado lógicamente.');
    }
}
