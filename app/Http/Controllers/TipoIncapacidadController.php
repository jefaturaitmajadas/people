<?php

namespace App\Http\Controllers;

use App\Models\TipoIncapacidad;
use Illuminate\Http\Request;

class TipoIncapacidadController extends Controller
{
    public function index()
    {
        $tipos = TipoIncapacidad::all();
        return view('tipo_incapacidad.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_incapacidad.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'porcentaje_por_dia' => 'required|numeric|min:0|max:100',
        ]);

        TipoIncapacidad::create($request->all());
        return redirect()->route('tipo-incapacidad.index')->with('success', 'Tipo de incapacidad registrado exitosamente.');
    }

    public function edit(TipoIncapacidad $tipo_incapacidad)
    {
        return view('tipo_incapacidad.edit', compact('tipo_incapacidad'));
    }

    public function update(Request $request, TipoIncapacidad $tipo_incapacidad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'porcentaje_por_dia' => 'required|numeric|min:0|max:100',
        ]);

        $tipo_incapacidad->update($request->all());
        return redirect()->route('tipo-incapacidad.index')->with('success', 'Tipo de incapacidad actualizado exitosamente.');
    }

    public function destroy(TipoIncapacidad $tipo_incapacidad)
    {
        $tipo_incapacidad->delete();
        return redirect()->route('tipo-incapacidad.index')->with('success', 'Tipo de incapacidad eliminado lógicamente.');
    }
}
