<?php

namespace App\Http\Controllers;

use App\Models\TipoSalario;
use App\Models\IgssTipoSalario;
use Illuminate\Http\Request;

class TipoSalarioController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $tipos = TipoSalario::with('igssTipoSalario')->get();
        return view('tipo_salario.index', compact('tipos'));
    }

    public function create()
    {
        $igssSalarios = IgssTipoSalario::all();
        return view('tipo_salario.create', compact('igssSalarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_igss_tipo_salario' => 'required|exists:igss_tipo_salario,id',
        ]);

        TipoSalario::create($request->all());
        return redirect()->route('tipo-salario.index')->with('success', 'Tipo de salario del sistema registrado exitosamente.');
    }

    public function edit(TipoSalario $tipo_salario)
    {
        $igssSalarios = IgssTipoSalario::all();
        return view('tipo_salario.edit', compact('tipo_salario', 'igssSalarios'));
    }

    public function update(Request $request, TipoSalario $tipo_salario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_igss_tipo_salario' => 'required|exists:igss_tipo_salario,id',
        ]);

        $tipo_salario->update($request->all());
        return redirect()->route('tipo-salario.index')->with('success', 'Tipo de salario del sistema actualizado exitosamente.');
    }

    public function destroy(TipoSalario $tipo_salario)
    {
        $tipo_salario->delete();
        return redirect()->route('tipo-salario.index')->with('success', 'Tipo de salario eliminado lógicamente.');
    }
}
