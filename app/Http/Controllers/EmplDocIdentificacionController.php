<?php

namespace App\Http\Controllers;

use App\Models\EmplDocIdentificacion;
use Illuminate\Http\Request;

class EmplDocIdentificacionController extends Controller
{
    public function index()
    {
        $documentos = EmplDocIdentificacion::all();
        return view('empl_doc_identificacion.index', compact('documentos'));
    }

    public function create()
    {
        return view('empl_doc_identificacion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'documento_identificacion' => 'required|string|max:255',
        ]);

        EmplDocIdentificacion::create($request->all());
        return redirect()->route('empl-doc-identificacion.index')->with('success', 'Documento registrado exitosamente.');
    }

    public function edit(EmplDocIdentificacion $doc_identificacion)
    {
        return view('empl_doc_identificacion.edit', compact('doc_identificacion'));
    }

    public function update(Request $request, EmplDocIdentificacion $doc_identificacion)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'documento_identificacion' => 'required|string|max:255',
        ]);

        $doc_identificacion->update($request->all());
        return redirect()->route('empl-doc-identificacion.index')->with('success', 'Documento actualizado exitosamente.');
    }

    public function destroy(EmplDocIdentificacion $doc_identificacion)
    {
        $doc_identificacion->delete();
        return redirect()->route('empl-doc-identificacion.index')->with('success', 'Documento eliminado lógicamente.');
    }
}