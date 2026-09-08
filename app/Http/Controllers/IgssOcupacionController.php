<?php

namespace App\Http\Controllers;

use App\Models\IgssOcupacion;
use Illuminate\Http\Request;

class IgssOcupacionController extends Controller
{
    public function index()
    {
        $ocupaciones = IgssOcupacion::all();
        return view('igss_ocupacion.index', compact('ocupaciones'));
    }

    public function create()
    {
        return view('igss_ocupacion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gran_grupo' => 'nullable|string|max:255',
            'sub_grupo_principal' => 'nullable|string|max:255',
            'sub_grupo' => 'nullable|string|max:255',
            'grupo_primario' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
        ]);

        IgssOcupacion::create($request->all());
        return redirect()->route('igss-ocupacion.index')->with('success', 'Ocupación registrada exitosamente.');
    }

    public function edit(IgssOcupacion $ocupacion)
    {
        return view('igss_ocupacion.edit', compact('ocupacion'));
    }

    public function update(Request $request, IgssOcupacion $ocupacion)
    {
        $request->validate([
            'gran_grupo' => 'nullable|string|max:255',
            'sub_grupo_principal' => 'nullable|string|max:255',
            'sub_grupo' => 'nullable|string|max:255',
            'grupo_primario' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $ocupacion->update($request->all());
        return redirect()->route('igss-ocupacion.index')->with('success', 'Ocupación actualizada exitosamente.');
    }

    public function destroy(IgssOcupacion $ocupacion)
    {
        $ocupacion->delete();
        return redirect()->route('igss-ocupacion.index')->with('success', 'Ocupación eliminada lógicamente.');
    }
}