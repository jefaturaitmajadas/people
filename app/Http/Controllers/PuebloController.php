<?php

namespace App\Http\Controllers;

use App\Models\Pueblo;
use App\Models\EmplPuebloPertenencia;
use Illuminate\Http\Request;

class PuebloController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $pueblos = Pueblo::with('emplPuebloPertenencia')->get();
        return view('pueblo.index', compact('pueblos'));
    }

    public function create()
    {
        $emplPueblos = EmplPuebloPertenencia::all();
        return view('pueblo.create', compact('emplPueblos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_pueblo_per' => 'required|exists:empl_pueblo_pertenencia,id',
        ]);

        Pueblo::create($request->all());
        return redirect()->route('pueblo.index')->with('success', 'Pueblo del sistema registrado exitosamente.');
    }

    public function edit(Pueblo $pueblo)
    {
        $emplPueblos = EmplPuebloPertenencia::all();
        return view('pueblo.edit', compact('pueblo', 'emplPueblos'));
    }

    public function update(Request $request, Pueblo $pueblo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_pueblo_per' => 'required|exists:empl_pueblo_pertenencia,id',
        ]);

        $pueblo->update($request->all());
        return redirect()->route('pueblo.index')->with('success', 'Pueblo del sistema actualizado exitosamente.');
    }

    public function destroy(Pueblo $pueblo)
    {
        $pueblo->delete();
        return redirect()->route('pueblo.index')->with('success', 'Pueblo eliminado lógicamente.');
    }
}
