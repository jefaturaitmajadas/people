<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use App\Models\EmplNacionalidad;
use App\Models\IgssPais;
use Illuminate\Http\Request;

class PaisController extends Controller
{
    public function index()
    {
        // Cargamos las relaciones para optimizar consultas
        $paises = Pais::with(['emplNacionalidad', 'igssPais'])->get();
        return view('pais.index', compact('paises'));
    }

    public function create()
    {
        $nacionalidades = EmplNacionalidad::all();
        $igssPaises = IgssPais::all();
        return view('pais.create', compact('nacionalidades', 'igssPaises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_nacionalidad' => 'required|exists:empl_nacionalidad,id',
            'id_igss_pais' => 'required|exists:igss_pais,id',
        ]);

        Pais::create($request->all());
        return redirect()->route('pais.index')->with('success', 'País principal registrado exitosamente.');
    }

    public function edit(Pais $pais)
    {
        $nacionalidades = EmplNacionalidad::all();
        $igssPaises = IgssPais::all();
        return view('pais.edit', compact('pais', 'nacionalidades', 'igssPaises'));
    }

    public function update(Request $request, Pais $pais)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_nacionalidad' => 'required|exists:empl_nacionalidad,id',
            'id_igss_pais' => 'required|exists:igss_pais,id',
        ]);

        $pais->update($request->all());
        return redirect()->route('pais.index')->with('success', 'País principal actualizado exitosamente.');
    }

    public function destroy(Pais $pais)
    {
        $pais->delete();
        return redirect()->route('pais.index')->with('success', 'País principal eliminado lógicamente.');
    }
}