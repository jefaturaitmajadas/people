<?php

namespace App\Http\Controllers;

use App\Models\EmplEstadoCivil;
use Illuminate\Http\Request;

class EmplEstadoCivilController extends Controller
{
    public function index()
    {
        $estados = EmplEstadoCivil::all();
        return view('empl_estado_civil.index', compact('estados'));
    }

    public function create()
    {
        return view('empl_estado_civil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'estado_civil' => 'required|string|max:255',
        ]);

        EmplEstadoCivil::create($request->all());
        return redirect()->route('empl-estado-civil.index')->with('success', 'Estado civil registrado exitosamente.');
    }

    public function edit(EmplEstadoCivil $estado_civil)
    {
        return view('empl_estado_civil.edit', compact('estado_civil'));
    }

    public function update(Request $request, EmplEstadoCivil $estado_civil)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'estado_civil' => 'required|string|max:255',
        ]);

        $estado_civil->update($request->all());
        return redirect()->route('empl-estado-civil.index')->with('success', 'Estado civil actualizado exitosamente.');
    }

    public function destroy(EmplEstadoCivil $estado_civil)
    {
        $estado_civil->delete();
        return redirect()->route('empl-estado-civil.index')->with('success', 'Estado civil eliminado lógicamente.');
    }
}