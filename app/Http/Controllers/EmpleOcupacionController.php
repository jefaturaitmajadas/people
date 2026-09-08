<?php

namespace App\Http\Controllers;

use App\Models\EmpleOcupacion;
use Illuminate\Http\Request;

class EmpleOcupacionController extends Controller
{
    public function index()
    {
        $ocupaciones = EmpleOcupacion::all();
        return view('emple_ocupacion.index', compact('ocupaciones'));
    }

    public function create()
    {
        return view('emple_ocupacion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'ocupacion' => 'required|string|max:255',
        ]);

        EmpleOcupacion::create($request->all());
        return redirect()->route('emple-ocupacion.index')->with('success', 'Ocupación registrada exitosamente.');
    }

    public function edit(EmpleOcupacion $ocupacion)
    {
        return view('emple_ocupacion.edit', compact('ocupacion'));
    }

    public function update(Request $request, EmpleOcupacion $ocupacion)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'ocupacion' => 'required|string|max:255',
        ]);

        $ocupacion->update($request->all());
        return redirect()->route('emple-ocupacion.index')->with('success', 'Ocupación actualizada exitosamente.');
    }

    public function destroy(EmpleOcupacion $ocupacion)
    {
        $ocupacion->delete();
        return redirect()->route('emple-ocupacion.index')->with('success', 'Ocupación eliminada lógicamente.');
    }
}