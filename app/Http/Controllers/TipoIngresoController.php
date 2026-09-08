<?php

namespace App\Http\Controllers;

use App\Models\TipoIngreso;
use Illuminate\Http\Request;

class TipoIngresoController extends Controller
{
    public function index()
    {
        $tipos = TipoIngreso::all();
        return view('tipo_ingresos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_ingresos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_ingresos,nombre'
        ]);

        TipoIngreso::create($request->all());

        return redirect()->route('tipo-ingresos.index')
                         ->with('success', 'Tipo de ingreso creado exitosamente.');
    }

    public function edit(TipoIngreso $tipo_ingreso)
    {
        return view('tipo_ingresos.edit', compact('tipo_ingreso'));
    }

    public function update(Request $request, TipoIngreso $tipo_ingreso)
    {
        $request->validate([
            // Se valida que sea único, pero ignorando el ID del registro actual
            'nombre' => 'required|string|max:255|unique:tipo_ingresos,nombre,' . $tipo_ingreso->id
        ]);

        $tipo_ingreso->update($request->all());

        return redirect()->route('tipo-ingresos.index')
                         ->with('success', 'Tipo de ingreso actualizado exitosamente.');
    }

    public function destroy(TipoIngreso $tipo_ingreso)
    {
        $tipo_ingreso->delete();

        return redirect()->route('tipo-ingresos.index')
                         ->with('success', 'Tipo de ingreso eliminado.');
    }
}
