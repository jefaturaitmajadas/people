<?php

namespace App\Http\Controllers;

use App\Models\TipoTransporte;
use Illuminate\Http\Request;

class TipoTransporteController extends Controller
{
    public function index()
    {
        $tipos = TipoTransporte::all();
        return view('tipo_transporte.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_transporte.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoTransporte::create($request->all());
        return redirect()->route('tipo-transporte.index')->with('success', 'Tipo de transporte registrado exitosamente.');
    }

    public function edit(TipoTransporte $tipo_transporte)
    {
        return view('tipo_transporte.edit', compact('tipo_transporte'));
    }

    public function update(Request $request, TipoTransporte $tipo_transporte)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_transporte->update($request->all());
        return redirect()->route('tipo-transporte.index')->with('success', 'Tipo de transporte actualizado exitosamente.');
    }

    public function destroy(TipoTransporte $tipo_transporte)
    {
        $tipo_transporte->delete();
        return redirect()->route('tipo-transporte.index')->with('success', 'Tipo de transporte eliminado lógicamente.');
    }
}
