<?php

namespace App\Http\Controllers;

use App\Models\Seguro;
use Illuminate\Http\Request;

class SeguroController extends Controller
{
    public function index()
    {
        $seguros = Seguro::all();
        return view('seguro.index', compact('seguros'));
    }

    public function create()
    {
        return view('seguro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Seguro::create($request->all());
        return redirect()->route('seguro.index')->with('success', 'Seguro registrado exitosamente.');
    }

    public function edit(Seguro $seguro)
    {
        return view('seguro.edit', compact('seguro'));
    }

    public function update(Request $request, Seguro $seguro)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $seguro->update($request->all());
        return redirect()->route('seguro.index')->with('success', 'Seguro actualizado exitosamente.');
    }

    public function destroy(Seguro $seguro)
    {
        $seguro->delete();
        return redirect()->route('seguro.index')->with('success', 'Seguro eliminado lógicamente.');
    }
}