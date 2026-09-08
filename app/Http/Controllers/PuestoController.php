<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    public function index()
    {
        $puestos = Puesto::all();
        return view('puesto.index', compact('puestos'));
    }

    public function create()
    {
        return view('puesto.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descriptor_de_puesto' => 'required|string',
            'responsabilidades' => 'required|string',
            'funciones_generales' => 'required|string',
            'funciones_especificas' => 'required|string',
            'salario_base' => 'required|numeric|min:0',
        ]);

        Puesto::create($request->all());
        return redirect()->route('puesto.index')->with('success', 'Puesto registrado exitosamente.');
    }

    public function edit(Puesto $puesto)
    {
        return view('puesto.edit', compact('puesto'));
    }

    public function update(Request $request, Puesto $puesto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descriptor_de_puesto' => 'required|string',
            'responsabilidades' => 'required|string',
            'funciones_generales' => 'required|string',
            'funciones_especificas' => 'required|string',
            'salario_base' => 'required|numeric|min:0',
        ]);

        $puesto->update($request->all());
        return redirect()->route('puesto.index')->with('success', 'Puesto actualizado exitosamente.');
    }

    public function destroy(Puesto $puesto)
    {
        $puesto->delete();
        return redirect()->route('puesto.index')->with('success', 'Puesto eliminado lógicamente.');
    }
}
