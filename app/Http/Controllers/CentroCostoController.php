<?php

namespace App\Http\Controllers;

use App\Models\CentroCosto;
use Illuminate\Http\Request;

class CentroCostoController extends Controller
{
    public function index()
    {
        $centros = CentroCosto::all();
        return view('centro_costo.index', compact('centros'));
    }

    public function create()
    {
        return view('centro_costo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_sap' => 'required|string|max:255',
        ]);

        CentroCosto::create($request->all());
        return redirect()->route('centro-costo.index')->with('success', 'Centro de costo registrado exitosamente.');
    }

    public function edit(CentroCosto $centro_costo)
    {
        return view('centro_costo.edit', compact('centro_costo'));
    }

    public function update(Request $request, CentroCosto $centro_costo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_sap' => 'required|string|max:255',
        ]);

        $centro_costo->update($request->all());
        return redirect()->route('centro-costo.index')->with('success', 'Centro de costo actualizado exitosamente.');
    }

    public function destroy(CentroCosto $centro_costo)
    {
        $centro_costo->delete();
        return redirect()->route('centro-costo.index')->with('success', 'Centro de costo eliminado lógicamente.');
    }
}
