<?php

namespace App\Http\Controllers;

use App\Models\TipoCasa;
use Illuminate\Http\Request;

class TipoCasaController extends Controller
{
    public function index()
    {
        $tipos = TipoCasa::all();
        return view('tipo_casa.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_casa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoCasa::create($request->all());
        return redirect()->route('tipo-casa.index')->with('success', 'Tipo de casa registrado exitosamente.');
    }

    public function edit(TipoCasa $tipo_casa)
    {
        return view('tipo_casa.edit', compact('tipo_casa'));
    }

    public function update(Request $request, TipoCasa $tipo_casa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_casa->update($request->all());
        return redirect()->route('tipo-casa.index')->with('success', 'Tipo de casa actualizado exitosamente.');
    }

    public function destroy(TipoCasa $tipo_casa)
    {
        $tipo_casa->delete();
        return redirect()->route('tipo-casa.index')->with('success', 'Tipo de casa eliminado lógicamente.');
    }
}
