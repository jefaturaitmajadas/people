<?php

namespace App\Http\Controllers;

use App\Models\EmplJornadaTrabajo;
use Illuminate\Http\Request;

class EmplJornadaTrabajoController extends Controller
{
    public function index()
    {
        $jornadas = EmplJornadaTrabajo::all();
        return view('empl_jornada_trabajo.index', compact('jornadas'));
    }

    public function create()
    {
        return view('empl_jornada_trabajo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'jornada_trabajo' => 'required|string|max:255',
        ]);

        EmplJornadaTrabajo::create($request->all());
        return redirect()->route('empl-jornada-trabajo.index')->with('success', 'Jornada de trabajo registrada exitosamente.');
    }

    public function edit(EmplJornadaTrabajo $jornada_trabajo)
    {
        return view('empl_jornada_trabajo.edit', compact('jornada_trabajo'));
    }

    public function update(Request $request, EmplJornadaTrabajo $jornada_trabajo)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'jornada_trabajo' => 'required|string|max:255',
        ]);

        $jornada_trabajo->update($request->all());
        return redirect()->route('empl-jornada-trabajo.index')->with('success', 'Jornada de trabajo actualizada exitosamente.');
    }

    public function destroy(EmplJornadaTrabajo $jornada_trabajo)
    {
        $jornada_trabajo->delete();
        return redirect()->route('empl-jornada-trabajo.index')->with('success', 'Jornada de trabajo eliminada lógicamente.');
    }
}