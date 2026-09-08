<?php

namespace App\Http\Controllers;

use App\Models\TituloAcademico;
use Illuminate\Http\Request;

class TituloAcademicoController extends Controller
{
    public function index()
    {
        $titulos = TituloAcademico::all();
        return view('titulo_academico.index', compact('titulos'));
    }

    public function create()
    {
        return view('titulo_academico.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        TituloAcademico::create($request->all());
        return redirect()->route('titulo-academico.index')->with('success', 'Título académico registrado exitosamente.');
    }

    public function edit(TituloAcademico $titulo_academico)
    {
        return view('titulo_academico.edit', compact('titulo_academico'));
    }

    public function update(Request $request, TituloAcademico $titulo_academico)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $titulo_academico->update($request->all());
        return redirect()->route('titulo-academico.index')->with('success', 'Título académico actualizado exitosamente.');
    }

    public function destroy(TituloAcademico $titulo_academico)
    {
        $titulo_academico->delete();
        return redirect()->route('titulo-academico.index')->with('success', 'Título académico eliminado lógicamente.');
    }
}
