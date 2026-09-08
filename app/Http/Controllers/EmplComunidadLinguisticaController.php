<?php

namespace App\Http\Controllers;

use App\Models\EmplComunidadLinguistica;
use Illuminate\Http\Request;

class EmplComunidadLinguisticaController extends Controller
{
    public function index()
    {
        $linguisticas = EmplComunidadLinguistica::all();
        return view('empl_linguistica.index', compact('linguisticas'));
    }

    public function create()
    {
        return view('empl_linguistica.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'comunidad_linguistica' => 'required|string|max:255',
        ]);

        EmplComunidadLinguistica::create($request->all());
        return redirect()->route('empl-linguistica.index')->with('success', 'Comunidad lingüística registrada exitosamente.');
    }

    public function edit(EmplComunidadLinguistica $linguistica)
    {
        return view('empl_linguistica.edit', compact('linguistica'));
    }

    public function update(Request $request, EmplComunidadLinguistica $linguistica)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'comunidad_linguistica' => 'required|string|max:255',
        ]);

        $linguistica->update($request->all());
        return redirect()->route('empl-linguistica.index')->with('success', 'Comunidad lingüística actualizada exitosamente.');
    }

    public function destroy(EmplComunidadLinguistica $linguistica)
    {
        $linguistica->delete();
        return redirect()->route('empl-linguistica.index')->with('success', 'Comunidad lingüística eliminada lógicamente.');
    }
}
