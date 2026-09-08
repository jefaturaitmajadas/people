<?php

namespace App\Http\Controllers;

use App\Models\EmplSexo;
use Illuminate\Http\Request;

class EmplSexoController extends Controller
{
    public function index()
    {
        $sexos = EmplSexo::all();
        return view('empl_sexo.index', compact('sexos'));
    }

    public function create()
    {
        return view('empl_sexo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'sexo' => 'required|string|max:255',
        ]);

        EmplSexo::create($request->all());
        return redirect()->route('empl-sexo.index')->with('success', 'Registro de sexo creado exitosamente.');
    }

    public function edit(EmplSexo $sexo)
    {
        return view('empl_sexo.edit', compact('sexo'));
    }

    public function update(Request $request, EmplSexo $sexo)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'sexo' => 'required|string|max:255',
        ]);

        $sexo->update($request->all());
        return redirect()->route('empl-sexo.index')->with('success', 'Registro de sexo actualizado exitosamente.');
    }

    public function destroy(EmplSexo $sexo)
    {
        $sexo->delete();
        return redirect()->route('empl-sexo.index')->with('success', 'Registro de sexo eliminado lógicamente.');
    }
}
