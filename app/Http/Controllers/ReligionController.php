<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
    public function index()
    {
        $religiones = Religion::all();
        return view('religion.index', compact('religiones'));
    }

    public function create()
    {
        return view('religion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Religion::create($request->all());
        return redirect()->route('religion.index')->with('success', 'Religión registrada exitosamente.');
    }

    public function edit(Religion $religion)
    {
        return view('religion.edit', compact('religion'));
    }

    public function update(Request $request, Religion $religion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $religion->update($request->all());
        return redirect()->route('religion.index')->with('success', 'Religión actualizada exitosamente.');
    }

    public function destroy(Religion $religion)
    {
        $religion->delete();
        return redirect()->route('religion.index')->with('success', 'Religión eliminada lógicamente.');
    }
}
