<?php

namespace App\Http\Controllers;

use App\Models\EmplTipoContrato;
use Illuminate\Http\Request;

class EmplTipoContratoController extends Controller
{
    public function index()
    {
        $tipos = EmplTipoContrato::all();
        return view('empl_tipo_contrato.index', compact('tipos'));
    }

    public function create()
    {
        return view('empl_tipo_contrato.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'tipo_contrato' => 'required|string|max:255',
        ]);

        EmplTipoContrato::create($request->all());
        return redirect()->route('empl-tipo-contrato.index')->with('success', 'Tipo de contrato registrado exitosamente.');
    }

    public function edit(EmplTipoContrato $tipo_contrato)
    {
        return view('empl_tipo_contrato.edit', compact('tipo_contrato'));
    }

    public function update(Request $request, EmplTipoContrato $tipo_contrato)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'tipo_contrato' => 'required|string|max:255',
        ]);

        $tipo_contrato->update($request->all());
        return redirect()->route('empl-tipo-contrato.index')->with('success', 'Tipo de contrato actualizado exitosamente.');
    }

    public function destroy(EmplTipoContrato $tipo_contrato)
    {
        $tipo_contrato->delete();
        return redirect()->route('empl-tipo-contrato.index')->with('success', 'Tipo de contrato eliminado lógicamente.');
    }
}
