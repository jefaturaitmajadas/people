<?php

namespace App\Http\Controllers;

use App\Models\GradoAcademico;
use Illuminate\Http\Request;

class GradoAcademicoController extends Controller
{
    public function index()
    {
        $grados = GradoAcademico::all();
        return view('grado_academico.index', compact('grados'));
    }

    public function create()
    {
        return view('grado_academico.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        GradoAcademico::create($request->all());
        return redirect()->route('grado-academico.index')->with('success', 'Grado académico registrado exitosamente.');
    }

    public function edit(GradoAcademico $grado_academico)
    {
        return view('grado_academico.edit', compact('grado_academico'));
    }

    public function update(Request $request, GradoAcademico $grado_academico)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $grado_academico->update($request->all());
        return redirect()->route('grado-academico.index')->with('success', 'Grado académico actualizado exitosamente.');
    }

    public function destroy(GradoAcademico $grado_academico)
    {
        $grado_academico->delete();
        return redirect()->route('grado-academico.index')->with('success', 'Grado académico eliminado lógicamente.');
    }
}
