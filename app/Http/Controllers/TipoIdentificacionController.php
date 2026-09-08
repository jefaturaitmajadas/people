<?php

namespace App\Http\Controllers;

use App\Models\TipoIdentificacion;
use App\Models\EmplDocIdentificacion;
use Illuminate\Http\Request;

class TipoIdentificacionController extends Controller
{
    public function index()
    {
        $tipos = TipoIdentificacion::with('emplDocIdentificacion')->get();
        return view('tipo_identificacions.index', compact('tipos'));
    }

    public function create()
    {
        $emplDocs = EmplDocIdentificacion::all();
        return view('tipo_identificacions.create', compact('emplDocs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_doc_ident' => 'required|exists:empl_doc_identificacion,id',
        ]);

        TipoIdentificacion::create($request->all());
        return redirect()->route('tipo_identificacions.index')->with('success', 'Tipo de identificación creado exitosamente.');
    }

    public function edit(TipoIdentificacion $tipoIdentificacion)
    {
        $emplDocs = EmplDocIdentificacion::all();
        return view('tipo_identificacions.edit', compact('tipoIdentificacion', 'emplDocs'));
    }

    public function update(Request $request, TipoIdentificacion $tipoIdentificacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_doc_ident' => 'required|exists:empl_doc_identificacion,id',
        ]);

        $tipoIdentificacion->update($request->all());
        return redirect()->route('tipo_identificacions.index')->with('success', 'Tipo de identificación actualizado exitosamente.');
    }

    public function destroy(TipoIdentificacion $tipoIdentificacion)
    {
        $tipoIdentificacion->delete();
        return redirect()->route('tipo_identificacions.index')->with('success', 'Tipo de identificación eliminado.');
    }
}