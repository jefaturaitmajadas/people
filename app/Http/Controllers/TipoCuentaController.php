<?php

namespace App\Http\Controllers;

use App\Models\TipoCuenta;
use Illuminate\Http\Request;

class TipoCuentaController extends Controller
{
    public function index()
    {
        $tipos = TipoCuenta::all();
        return view('tipo_cuenta.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_cuenta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoCuenta::create($request->all());
        return redirect()->route('tipo-cuenta.index')->with('success', 'Tipo de cuenta registrado exitosamente.');
    }

    public function edit(TipoCuenta $tipo_cuenta)
    {
        return view('tipo_cuenta.edit', compact('tipo_cuenta'));
    }

    public function update(Request $request, TipoCuenta $tipo_cuenta)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_cuenta->update($request->all());
        return redirect()->route('tipo-cuenta.index')->with('success', 'Tipo de cuenta actualizado exitosamente.');
    }

    public function destroy(TipoCuenta $tipo_cuenta)
    {
        $tipo_cuenta->delete();
        return redirect()->route('tipo-cuenta.index')->with('success', 'Tipo de cuenta eliminado lógicamente.');
    }
}
