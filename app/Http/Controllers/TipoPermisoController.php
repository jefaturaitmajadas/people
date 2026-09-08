<?php

namespace App\Http\Controllers;

use App\Models\TipoPermiso;
use Illuminate\Http\Request;

class TipoPermisoController extends Controller
{
    public function index()
    {
        $tipos = TipoPermiso::all();
        return view('tipo_permiso.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_permiso.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoPermiso::create($request->all());
        return redirect()->route('tipo-permiso.index')->with('success', 'Tipo de permiso registrado exitosamente.');
    }

    public function edit(TipoPermiso $tipo_permiso)
    {
        return view('tipo_permiso.edit', compact('tipo_permiso'));
    }

    public function update(Request $request, TipoPermiso $tipo_permiso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_permiso->update($request->all());
        return redirect()->route('tipo-permiso.index')->with('success', 'Tipo de permiso actualizado exitosamente.');
    }

    public function destroy(TipoPermiso $tipo_permiso)
    {
        $tipo_permiso->delete();
        return redirect()->route('tipo-permiso.index')->with('success', 'Tipo de permiso eliminado lógicamente.');
    }
}
