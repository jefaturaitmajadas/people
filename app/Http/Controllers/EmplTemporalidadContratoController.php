<?php

namespace App\Http\Controllers;

use App\Models\EmplTemporalidadContrato;
use Illuminate\Http\Request;

class EmplTemporalidadContratoController extends Controller
{
    public function index()
    {
        $temporalidades = EmplTemporalidadContrato::all();
        return view('empl_temporalidad_contrato.index', compact('temporalidades'));
    }

    public function create()
    {
        return view('empl_temporalidad_contrato.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'temporalidad_contrato' => 'required|string|max:255',
        ]);

        EmplTemporalidadContrato::create($request->all());
        return redirect()->route('empl-temp-contrato.index')->with('success', 'Temporalidad de contrato registrada exitosamente.');
    }

    public function edit(EmplTemporalidadContrato $temporalidad)
    {
        return view('empl_temporalidad_contrato.edit', compact('temporalidad'));
    }

    public function update(Request $request, EmplTemporalidadContrato $temporalidad)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'temporalidad_contrato' => 'required|string|max:255',
        ]);

        $temporalidad->update($request->all());
        return redirect()->route('empl-temp-contrato.index')->with('success', 'Temporalidad de contrato actualizada exitosamente.');
    }

    public function destroy(EmplTemporalidadContrato $temporalidad)
    {
        $temporalidad->delete();
        return redirect()->route('empl-temp-contrato.index')->with('success', 'Temporalidad de contrato eliminada lógicamente.');
    }
}
