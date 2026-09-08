<?php

namespace App\Http\Controllers;

use App\Models\EmpresaCuenta;
use App\Models\Banco;
use Illuminate\Http\Request;

class EmpresaCuentaController extends Controller
{
    public function index()
    {   
        // Gracias a nuestro Trait, esto SOLO trae las cuentas de la empresa seleccionada en sesión
        $cuentas = EmpresaCuenta::with('banco')->get();
        return view('empresa_cuentas.index', compact('cuentas'));
    }

    public function create()
    {
        // Traemos el catálogo de bancos para llenar el select
        $bancos = Banco::all(); 
        return view('empresa_cuentas.create', compact('bancos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:banco,id',
            'numero_cuenta' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:255',
        ]);

        // Al usar ::create(), el Trait 'ScopesByEmpresa' inyectará el empresa_id automáticamente
        EmpresaCuenta::create([
            'banco_id' => $request->banco_id,
            'numero_cuenta' => $request->numero_cuenta,
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? true : false,
        ]);

        return redirect()->route('empresa-cuentas.index')->with('success', 'Cuenta bancaria registrada exitosamente.');
    }

    public function edit(EmpresaCuenta $empresaCuenta)
    {
        $bancos = Banco::all();
        return view('empresa_cuentas.edit', compact('empresaCuenta', 'bancos'));
    }

    public function update(Request $request, EmpresaCuenta $empresaCuenta)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'numero_cuenta' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $empresaCuenta->update([
            'banco_id' => $request->banco_id,
            'numero_cuenta' => $request->numero_cuenta,
            'descripcion' => $request->descripcion,
            'estado' => $request->has('estado') ? true : false,
        ]);

        return redirect()->route('empresa-cuentas.index')->with('success', 'Cuenta bancaria actualizada correctamente.');
    }

    public function destroy(EmpresaCuenta $empresaCuenta)
    {
        $empresaCuenta->delete();
        return redirect()->route('empresa-cuentas.index')->with('success', 'Cuenta bancaria eliminada.');
    }
}