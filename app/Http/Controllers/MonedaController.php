<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    public function index()
    {
        $monedas = Moneda::all();
        return view('moneda.index', compact('monedas'));
    }

    public function create()
    {
        return view('moneda.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'simbolo' => 'required|string|max:10',
        ]);

        Moneda::create($request->all());
        return redirect()->route('moneda.index')->with('success', 'Moneda registrada exitosamente.');
    }

    public function edit(Moneda $moneda)
    {
        return view('moneda.edit', compact('moneda'));
    }

    public function update(Request $request, Moneda $moneda)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'simbolo' => 'required|string|max:10',
        ]);

        $moneda->update($request->all());
        return redirect()->route('moneda.index')->with('success', 'Moneda actualizada exitosamente.');
    }

    public function destroy(Moneda $moneda)
    {
        $moneda->delete();
        return redirect()->route('moneda.index')->with('success', 'Moneda eliminada lógicamente.');
    }
}