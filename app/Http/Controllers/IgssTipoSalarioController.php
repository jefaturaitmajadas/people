<?php

namespace App\Http\Controllers;

use App\Models\IgssTipoSalario;
use Illuminate\Http\Request;

class IgssTipoSalarioController extends Controller
{
    public function index()
    {
        $tipos = IgssTipoSalario::all();
        return view('igss_tipo_salario.index', compact('tipos'));
    }

    public function create()
    {
        return view('igss_tipo_salario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'descripcion' => 'required|string|max:255',
        ]);

        IgssTipoSalario::create($request->all());
        return redirect()->route('igss-tipo-salario.index')->with('success', 'Tipo de salario registrado exitosamente.');
    }

    public function edit(IgssTipoSalario $tipo_salario)
    {
        return view('igss_tipo_salario.edit', compact('tipo_salario'));
    }

    public function update(Request $request, IgssTipoSalario $tipo_salario)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'descripcion' => 'required|string|max:255',
        ]);

        $tipo_salario->update($request->all());
        return redirect()->route('igss-tipo-salario.index')->with('success', 'Tipo de salario actualizado exitosamente.');
    }

    public function destroy(IgssTipoSalario $tipo_salario)
    {
        $tipo_salario->delete();
        return redirect()->route('igss-tipo-salario.index')->with('success', 'Tipo de salario eliminado lógicamente.');
    }
}
