<?php

namespace App\Http\Controllers;

use App\Models\TipoContrato;
use App\Models\EmplTemporalidadContrato;
use Illuminate\Http\Request;

class TipoContratoController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $tipos = TipoContrato::with('emplTemporalidadContrato')->get();
        return view('tipo_contrato.index', compact('tipos'));
    }

    public function create()
    {
        $temporalidades = EmplTemporalidadContrato::all();
        return view('tipo_contrato.create', compact('temporalidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_temp_con' => 'required|exists:empl_temporalidad_contrato,id',
        ]);

        TipoContrato::create($request->all());
        return redirect()->route('tipo-contrato.index')->with('success', 'Tipo de contrato registrado exitosamente.');
    }

    public function edit(TipoContrato $tipo_contrato)
    {
        $temporalidades = EmplTemporalidadContrato::all();
        return view('tipo_contrato.edit', compact('tipo_contrato', 'temporalidades'));
    }

    public function update(Request $request, TipoContrato $tipo_contrato)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_temp_con' => 'required|exists:empl_temporalidad_contrato,id',
        ]);

        $tipo_contrato->update($request->all());
        return redirect()->route('tipo-contrato.index')->with('success', 'Tipo de contrato actualizado exitosamente.');
    }

    public function destroy(TipoContrato $tipo_contrato)
    {
        $tipo_contrato->delete();
        return redirect()->route('tipo-contrato.index')->with('success', 'Tipo de contrato eliminado lógicamente.');
    }
}