<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::all();
        return view('tipo_documento.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_documento.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoDocumento::create($request->all());
        return redirect()->route('tipo-documento.index')->with('success', 'Tipo de documento registrado exitosamente.');
    }

    public function edit(TipoDocumento $tipo_documento)
    {
        return view('tipo_documento.edit', compact('tipo_documento'));
    }

    public function update(Request $request, TipoDocumento $tipo_documento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_documento->update($request->all());
        return redirect()->route('tipo-documento.index')->with('success', 'Tipo de documento actualizado exitosamente.');
    }

    public function destroy(TipoDocumento $tipo_documento)
    {
        $tipo_documento->delete();
        return redirect()->route('tipo-documento.index')->with('success', 'Tipo de documento eliminado lógicamente.');
    }
}
