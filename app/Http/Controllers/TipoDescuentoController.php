<?php

namespace App\Http\Controllers;

use App\Models\TipoDescuento;
use Illuminate\Http\Request;

class TipoDescuentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDescuento::all();
        return view('tipo_descuentos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_descuentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_descuentos,nombre'
        ]);

        TipoDescuento::create($request->all());

        return redirect()->route('tipo-descuentos.index')
                         ->with('success', 'Tipo de descuento creado exitosamente.');
    }

    public function edit(TipoDescuento $tipo_descuento)
    {
        return view('tipo_descuentos.edit', compact('tipo_descuento'));
    }

    public function update(Request $request, TipoDescuento $tipo_descuento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_descuentos,nombre,' . $tipo_descuento->id
        ]);

        $tipo_descuento->update($request->all());

        return redirect()->route('tipo-descuentos.index')
                         ->with('success', 'Tipo de descuento actualizado exitosamente.');
    }

    public function destroy(TipoDescuento $tipo_descuento)
    {
        // Validar si el tipo de descuento está en uso antes de eliminarlo
        // (Opcional, pero recomendado si tienes softdeletes o cascada)
        $tipo_descuento->delete();

        return redirect()->route('tipo-descuentos.index')
                         ->with('success', 'Tipo de descuento eliminado.');
    }
}
