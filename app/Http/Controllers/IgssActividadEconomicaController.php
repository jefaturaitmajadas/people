<?php

namespace App\Http\Controllers;

use App\Models\IgssActividadEconomica;
use Illuminate\Http\Request;

class IgssActividadEconomicaController extends Controller
{
    public function index()
    {
        $actividades = IgssActividadEconomica::all();
        return view('actividad_economica.index', compact('actividades'));
    }

    public function create()
    {
        return view('actividad_economica.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|max:1',
            'division' => 'nullable|string|max:255',
            'grupo' => 'nullable|string|max:255',
            'clase' => 'nullable|string|max:255',
            'clasificacion' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
        ]);

        IgssActividadEconomica::create($request->all());
        return redirect()->route('actividad-economica.index')->with('success', 'Actividad Económica registrada exitosamente.');
    }

    public function edit(IgssActividadEconomica $actividad_economica)
    {
        // En la vista usaremos $actividad en lugar del nombre tan largo
        $actividad = $actividad_economica; 
        return view('actividad_economica.edit', compact('actividad'));
    }

    public function update(Request $request, IgssActividadEconomica $actividad_economica)
    {
        $request->validate([
            'categoria' => 'required|string|max:1',
            'division' => 'nullable|string|max:255',
            'grupo' => 'nullable|string|max:255',
            'clase' => 'nullable|string|max:255',
            'clasificacion' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $actividad_economica->update($request->all());
        return redirect()->route('actividad-economica.index')->with('success', 'Actividad Económica actualizada exitosamente.');
    }

    public function destroy(IgssActividadEconomica $actividad_economica)
    {
        $actividad_economica->delete();
        return redirect()->route('actividad-economica.index')->with('success', 'Actividad Económica eliminada lógicamente.');
    }
}