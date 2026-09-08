<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\EmplJornadaTrabajo;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $turnos = Turno::with('emplJornadaTrabajo')->get();
        return view('turno.index', compact('turnos'));
    }

    public function create()
    {
        $jornadas = EmplJornadaTrabajo::all();
        return view('turno.create', compact('jornadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_jornada' => 'required|exists:empl_jornada_trabajo,id',
        ]);

        Turno::create($request->all());
        return redirect()->route('turno.index')->with('success', 'Turno del sistema registrado exitosamente.');
    }

    public function edit(Turno $turno)
    {
        $jornadas = EmplJornadaTrabajo::all();
        return view('turno.edit', compact('turno', 'jornadas'));
    }

    public function update(Request $request, Turno $turno)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_jornada' => 'required|exists:empl_jornada_trabajo,id',
        ]);

        $turno->update($request->all());
        return redirect()->route('turno.index')->with('success', 'Turno del sistema actualizado exitosamente.');
    }

    public function destroy(Turno $turno)
    {
        $turno->delete();
        return redirect()->route('turno.index')->with('success', 'Turno eliminado lógicamente.');
    }
}
