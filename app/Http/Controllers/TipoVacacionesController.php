<?php

namespace App\Http\Controllers;

use App\Models\TipoVacaciones;
use Illuminate\Http\Request;

class TipoVacacionesController extends Controller
{
    public function index()
    {
        $tipos = TipoVacaciones::all();
        return view('tipo_vacaciones.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_vacaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoVacaciones::create($request->all());
        return redirect()->route('tipo-vacaciones.index')->with('success', 'Tipo de vacaciones registrado exitosamente.');
    }

    public function edit(TipoVacaciones $tipo_vacaciones)
    {
        return view('tipo_vacaciones.edit', compact('tipo_vacaciones'));
    }

    public function update(Request $request, TipoVacaciones $tipo_vacaciones)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_vacaciones->update($request->all());
        return redirect()->route('tipo-vacaciones.index')->with('success', 'Tipo de vacaciones actualizado exitosamente.');
    }

    public function destroy(TipoVacaciones $tipo_vacaciones)
    {
        $tipo_vacaciones->delete();
        return redirect()->route('tipo-vacaciones.index')->with('success', 'Tipo de vacaciones eliminado lógicamente.');
    }
}
