<?php

namespace App\Http\Controllers;

use App\Models\IgssPais;
use Illuminate\Http\Request;

class IgssPaisController extends Controller
{
    public function index()
    {
        $paises = IgssPais::all();
        return view('paises.index', compact('paises'));
    }

    public function create()
    {
        return view('paises.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'campo' => 'required|string|max:255',
        ]);

        IgssPais::create($request->all());
        // Actualizado: redirección a igss-paises.index
        return redirect()->route('igss-paises.index')->with('success', 'País registrado exitosamente.');
    }

    public function edit(IgssPais $pais) // Variable limpia gracias al routes/web.php
    {
        return view('paises.edit', compact('pais'));
    }

    public function update(Request $request, IgssPais $pais)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'campo' => 'required|string|max:255',
        ]);

        $pais->update($request->all());
        // Actualizado: redirección a igss-paises.index
        return redirect()->route('igss-paises.index')->with('success', 'País actualizado exitosamente.');
    }

    public function destroy(IgssPais $pais)
    {
        $pais->delete();
        // Actualizado: redirección a igss-paises.index
        return redirect()->route('igss-paises.index')->with('success', 'País eliminado lógicamente.');
    }
}