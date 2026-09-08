<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\EmplSexo;
use Illuminate\Http\Request;

class GeneroController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $generos = Genero::with('emplSexo')->get();
        return view('genero.index', compact('generos'));
    }

    public function create()
    {
        $emplSexos = EmplSexo::all();
        return view('genero.create', compact('emplSexos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_sexo' => 'required|exists:empl_sexo,id',
        ]);

        Genero::create($request->all());
        return redirect()->route('genero.index')->with('success', 'Género del sistema registrado exitosamente.');
    }

    public function edit(Genero $genero)
    {
        $emplSexos = EmplSexo::all();
        return view('genero.edit', compact('genero', 'emplSexos'));
    }

    public function update(Request $request, Genero $genero)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_sexo' => 'required|exists:empl_sexo,id',
        ]);

        $genero->update($request->all());
        return redirect()->route('genero.index')->with('success', 'Género del sistema actualizado exitosamente.');
    }

    public function destroy(Genero $genero)
    {
        $genero->delete();
        return redirect()->route('genero.index')->with('success', 'Género eliminado lógicamente.');
    }
}