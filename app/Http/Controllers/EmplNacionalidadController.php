<?php

namespace App\Http\Controllers;

use App\Models\EmplNacionalidad;
use Illuminate\Http\Request;

class EmplNacionalidadController extends Controller
{
    public function index()
    {
        $nacionalidades = EmplNacionalidad::all();
        return view('nacionalidad_pais.index', compact('nacionalidades'));
    }

    public function create()
    {
        return view('nacionalidad_pais.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
        ]);

        EmplNacionalidad::create($request->all());
        return redirect()->route('nacionalidad-pais.index')->with('success', 'Nacionalidad registrada exitosamente.');
    }

    public function edit(EmplNacionalidad $nacionalidad)
    {
        return view('nacionalidad_pais.edit', compact('nacionalidad'));
    }

    public function update(Request $request, EmplNacionalidad $nacionalidad)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
        ]);

        $nacionalidad->update($request->all());
        return redirect()->route('nacionalidad-pais.index')->with('success', 'Nacionalidad actualizada exitosamente.');
    }

    public function destroy(EmplNacionalidad $nacionalidad)
    {
        $nacionalidad->delete();
        return redirect()->route('nacionalidad-pais.index')->with('success', 'Nacionalidad eliminada lógicamente.');
    }
}
