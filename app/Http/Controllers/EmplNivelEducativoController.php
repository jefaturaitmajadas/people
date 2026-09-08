<?php

namespace App\Http\Controllers;

use App\Models\EmplNivelEducativo;
use Illuminate\Http\Request;

class EmplNivelEducativoController extends Controller
{
    public function index()
    {
        $niveles = EmplNivelEducativo::all();
        return view('empl_nivel_educativo.index', compact('niveles'));
    }

    public function create()
    {
        return view('empl_nivel_educativo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'nivel_educativo' => 'required|string|max:255',
        ]);

        EmplNivelEducativo::create($request->all());
        return redirect()->route('empl-nivel-educativo.index')->with('success', 'Nivel educativo registrado exitosamente.');
    }

    public function edit(EmplNivelEducativo $nivel_educativo)
    {
        return view('empl_nivel_educativo.edit', compact('nivel_educativo'));
    }

    public function update(Request $request, EmplNivelEducativo $nivel_educativo)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'nivel_educativo' => 'required|string|max:255',
        ]);

        $nivel_educativo->update($request->all());
        return redirect()->route('empl-nivel-educativo.index')->with('success', 'Nivel educativo actualizado exitosamente.');
    }

    public function destroy(EmplNivelEducativo $nivel_educativo)
    {
        $nivel_educativo->delete();
        return redirect()->route('empl-nivel-educativo.index')->with('success', 'Nivel educativo eliminado lógicamente.');
    }
}
