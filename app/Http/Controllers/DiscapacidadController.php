<?php

namespace App\Http\Controllers;

use App\Models\Discapacidad;
use App\Models\EmplTipoDiscapacidad;
use Illuminate\Http\Request;

class DiscapacidadController extends Controller
{
    public function index()
    {
        // Cargamos la relación para no hacer múltiples consultas a la base de datos
        $discapacidades = Discapacidad::with('emplTipoDiscapacidad')->get();
        return view('discapacidad.index', compact('discapacidades'));
    }

    public function create()
    {
        $tiposDiscapacidad = EmplTipoDiscapacidad::all();
        return view('discapacidad.create', compact('tiposDiscapacidad'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_tipo_capacidad' => 'required|exists:empl_tipo_discapacidad,id',
        ]);

        Discapacidad::create($request->all());
        return redirect()->route('discapacidad.index')->with('success', 'Discapacidad del sistema registrada exitosamente.');
    }

    public function edit(Discapacidad $discapacidad)
    {
        $tiposDiscapacidad = EmplTipoDiscapacidad::all();
        return view('discapacidad.edit', compact('discapacidad', 'tiposDiscapacidad'));
    }

    public function update(Request $request, Discapacidad $discapacidad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_empl_tipo_capacidad' => 'required|exists:empl_tipo_discapacidad,id',
        ]);

        $discapacidad->update($request->all());
        return redirect()->route('discapacidad.index')->with('success', 'Discapacidad del sistema actualizada exitosamente.');
    }

    public function destroy(Discapacidad $discapacidad)
    {
        $discapacidad->delete();
        return redirect()->route('discapacidad.index')->with('success', 'Discapacidad eliminada lógicamente.');
    }
}