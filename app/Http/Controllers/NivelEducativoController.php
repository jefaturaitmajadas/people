<?php

namespace App\Http\Controllers;

use App\Models\NivelEducativo;
use App\Models\EmplNivelEducativo;
use Illuminate\Http\Request;

class NivelEducativoController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $niveles = NivelEducativo::with('emplNivelEducativo')->get();
        return view('nivel_educativo.index', compact('niveles'));
    }

    public function create()
    {
        $emplNiveles = EmplNivelEducativo::all();
        return view('nivel_educativo.create', compact('emplNiveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_nivel_edu' => 'required|exists:empl_nivel_educativo,id',
        ]);

        NivelEducativo::create($request->all());
        return redirect()->route('nivel-educativo.index')->with('success', 'Nivel educativo del sistema registrado exitosamente.');
    }

    public function edit(NivelEducativo $nivel_educativo)
    {
        $emplNiveles = EmplNivelEducativo::all();
        return view('nivel_educativo.edit', compact('nivel_educativo', 'emplNiveles'));
    }

    public function update(Request $request, NivelEducativo $nivel_educativo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_nivel_edu' => 'required|exists:empl_nivel_educativo,id',
        ]);

        $nivel_educativo->update($request->all());
        return redirect()->route('nivel-educativo.index')->with('success', 'Nivel educativo del sistema actualizado exitosamente.');
    }

    public function destroy(NivelEducativo $nivel_educativo)
    {
        $nivel_educativo->delete();
        return redirect()->route('nivel-educativo.index')->with('success', 'Nivel educativo eliminado lógicamente.');
    }
}
