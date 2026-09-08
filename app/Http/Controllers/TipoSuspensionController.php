<?php

namespace App\Http\Controllers;

use App\Models\TipoSuspension;
use Illuminate\Http\Request;

class TipoSuspensionController extends Controller
{
    public function index()
    {
        $tipos = TipoSuspension::all();
        return view('tipo_suspension.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_suspension.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoSuspension::create($request->all());
        return redirect()->route('tipo-suspension.index')->with('success', 'Tipo de suspensión registrado exitosamente.');
    }

    public function edit(TipoSuspension $tipo_suspension)
    {
        return view('tipo_suspension.edit', compact('tipo_suspension'));
    }

    public function update(Request $request, TipoSuspension $tipo_suspension)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_suspension->update($request->all());
        return redirect()->route('tipo-suspension.index')->with('success', 'Tipo de suspensión actualizado exitosamente.');
    }

    public function destroy(TipoSuspension $tipo_suspension)
    {
        $tipo_suspension->delete();
        return redirect()->route('tipo-suspension.index')->with('success', 'Tipo de suspensión eliminado lógicamente.');
    }
}
