<?php

namespace App\Http\Controllers;

use App\Models\TipoPago;
use Illuminate\Http\Request;

class TipoPagoController extends Controller
{
    public function index()
    {
        $tipos = TipoPago::all();
        return view('tipo_pago.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_pago.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TipoPago::create($request->all());
        return redirect()->route('tipo-pago.index')->with('success', 'Tipo de pago registrado exitosamente.');
    }

    public function edit(TipoPago $tipo_pago)
    {
        return view('tipo_pago.edit', compact('tipo_pago'));
    }

    public function update(Request $request, TipoPago $tipo_pago)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo_pago->update($request->all());
        return redirect()->route('tipo-pago.index')->with('success', 'Tipo de pago actualizado exitosamente.');
    }

    public function destroy(TipoPago $tipo_pago)
    {
        $tipo_pago->delete();
        return redirect()->route('tipo-pago.index')->with('success', 'Tipo de pago eliminado lógicamente.');
    }
}