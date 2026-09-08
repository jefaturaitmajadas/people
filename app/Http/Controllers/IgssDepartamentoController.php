<?php

namespace App\Http\Controllers;

use App\Models\IgssDepartamento;
use Illuminate\Http\Request;

class IgssDepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = IgssDepartamento::all();
        return view('igss_departamento.index', compact('departamentos'));
    }

    public function create()
    {
        return view('igss_departamento.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'departamento' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
        ]);

        IgssDepartamento::create($request->all());
        return redirect()->route('igss-departamento.index')->with('success', 'Departamento del IGSS registrado exitosamente.');
    }

    public function edit(IgssDepartamento $departamento)
    {
        return view('igss_departamento.edit', compact('departamento'));
    }

    public function update(Request $request, IgssDepartamento $departamento)
    {
        $request->validate([
            'departamento' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
        ]);

        $departamento->update($request->all());
        return redirect()->route('igss-departamento.index')->with('success', 'Departamento del IGSS actualizado exitosamente.');
    }

    public function destroy(IgssDepartamento $departamento)
    {
        $departamento->delete();
        return redirect()->route('igss-departamento.index')->with('success', 'Departamento del IGSS eliminado lógicamente.');
    }
}
