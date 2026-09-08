<?php

namespace App\Http\Controllers;

use App\Models\EmplPuebloPertenencia;
use Illuminate\Http\Request;

class EmplPuebloPertenenciaController extends Controller
{
    public function index()
    {
        $pueblos = EmplPuebloPertenencia::all();
        return view('empl_pueblo.index', compact('pueblos'));
    }

    public function create()
    {
        return view('empl_pueblo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'pueblo_de_pertenencia' => 'required|string|max:255',
        ]);

        EmplPuebloPertenencia::create($request->all());
        return redirect()->route('empl-pueblo.index')->with('success', 'Pueblo de pertenencia registrado exitosamente.');
    }

    public function edit(EmplPuebloPertenencia $pueblo)
    {
        return view('empl_pueblo.edit', compact('pueblo'));
    }

    public function update(Request $request, EmplPuebloPertenencia $pueblo)
    {
        $request->validate([
            'codigo' => 'required|integer',
            'pueblo_de_pertenencia' => 'required|string|max:255',
        ]);

        $pueblo->update($request->all());
        return redirect()->route('empl-pueblo.index')->with('success', 'Pueblo de pertenencia actualizado exitosamente.');
    }

    public function destroy(EmplPuebloPertenencia $pueblo)
    {
        $pueblo->delete();
        return redirect()->route('empl-pueblo.index')->with('success', 'Pueblo de pertenencia eliminado lógicamente.');
    }
}
