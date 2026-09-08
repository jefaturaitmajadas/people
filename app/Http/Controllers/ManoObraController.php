<?php

namespace App\Http\Controllers;

use App\Models\ManoObra;
use Illuminate\Http\Request;

class ManoObraController extends Controller
{
    public function index()
    {
        $manos_obra = ManoObra::all();
        return view('mano_obra.index', compact('manos_obra'));
    }

    public function create()
    {
        return view('mano_obra.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        ManoObra::create($request->all());
        return redirect()->route('mano-obra.index')->with('success', 'Mano de obra registrada exitosamente.');
    }

    public function edit(ManoObra $mano_obra)
    {
        return view('mano_obra.edit', compact('mano_obra'));
    }

    public function update(Request $request, ManoObra $mano_obra)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $mano_obra->update($request->all());
        return redirect()->route('mano-obra.index')->with('success', 'Mano de obra actualizada exitosamente.');
    }

    public function destroy(ManoObra $mano_obra)
    {
        $mano_obra->delete();
        return redirect()->route('mano-obra.index')->with('success', 'Mano de obra eliminada lógicamente.');
    }
}
