<?php

namespace App\Http\Controllers;

use App\Models\EmplTipoDiscapacidad;
use Illuminate\Http\Request;

class EmplTipoDiscapacidadController extends Controller
{
    public function index()
    {
        $discapacidades = EmplTipoDiscapacidad::all();
        return view('empl_discapacidad.index', compact('discapacidades'));
    }

    public function create()
    {
        return view('empl_discapacidad.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'discapacidad' => 'required|string|max:255',
        ]);

        EmplTipoDiscapacidad::create($request->all());
        return redirect()->route('empl-discapacidad.index')->with('success', 'Tipo de discapacidad registrado exitosamente.');
    }

    public function edit(EmplTipoDiscapacidad $discapacidad)
    {
        return view('empl_discapacidad.edit', compact('discapacidad'));
    }

    public function update(Request $request, EmplTipoDiscapacidad $discapacidad)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'discapacidad' => 'required|string|max:255',
        ]);

        $discapacidad->update($request->all());
        return redirect()->route('empl-discapacidad.index')->with('success', 'Tipo de discapacidad actualizado exitosamente.');
    }

    public function destroy(EmplTipoDiscapacidad $discapacidad)
    {
        $discapacidad->delete();
        return redirect()->route('empl-discapacidad.index')->with('success', 'Tipo de discapacidad eliminado lógicamente.');
    }
}