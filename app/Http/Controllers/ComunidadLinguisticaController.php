<?php

namespace App\Http\Controllers;

use App\Models\ComunidadLinguistica;
use App\Models\EmplComunidadLinguistica;
use Illuminate\Http\Request;

class ComunidadLinguisticaController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $comunidades = ComunidadLinguistica::with('emplComunidadLinguistica')->get();
        return view('comunidad_linguistica.index', compact('comunidades'));
    }

    public function create()
    {
        $emplLinguisticas = EmplComunidadLinguistica::all();
        return view('comunidad_linguistica.create', compact('emplLinguisticas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_linguistica' => 'required|exists:empl_comunidad_linguistica,id',
        ]);

        ComunidadLinguistica::create($request->all());
        return redirect()->route('comunidad-linguistica.index')->with('success', 'Comunidad lingüística del sistema registrada exitosamente.');
    }

    public function edit(ComunidadLinguistica $comunidad_linguistica)
    {
        $emplLinguisticas = EmplComunidadLinguistica::all();
        return view('comunidad_linguistica.edit', compact('comunidad_linguistica', 'emplLinguisticas'));
    }

    public function update(Request $request, ComunidadLinguistica $comunidad_linguistica)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_linguistica' => 'required|exists:empl_comunidad_linguistica,id',
        ]);

        $comunidad_linguistica->update($request->all());
        return redirect()->route('comunidad-linguistica.index')->with('success', 'Comunidad lingüística del sistema actualizada exitosamente.');
    }

    public function destroy(ComunidadLinguistica $comunidad_linguistica)
    {
        $comunidad_linguistica->delete();
        return redirect()->route('comunidad-linguistica.index')->with('success', 'Comunidad lingüística eliminada lógicamente.');
    }
}
