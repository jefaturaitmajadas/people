<?php

namespace App\Http\Controllers;

use App\Models\Ocupacion;
use App\Models\EmpleOcupacion;
use App\Models\IgssOcupacion;
use Illuminate\Http\Request;

class OcupacionController extends Controller
{
    public function index()
    {
        // Cargamos las relaciones para optimizar consultas
        $ocupaciones = Ocupacion::with(['empleOcupacion', 'igssOcupacion'])->get();
        return view('ocupacion.index', compact('ocupaciones'));
    }

    public function create()
    {
        $empleOcupaciones = EmpleOcupacion::all();
        $igssOcupaciones = IgssOcupacion::all();
        return view('ocupacion.create', compact('empleOcupaciones', 'igssOcupaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_emple_ocupacion' => 'required|exists:emple_ocupacion,id',
            'id_igss_ocupacion' => 'required|exists:igss_ocupacion,id',
        ]);

        Ocupacion::create($request->all());
        return redirect()->route('ocupacion.index')->with('success', 'Ocupación del sistema registrada exitosamente.');
    }

    public function edit(Ocupacion $ocupacion)
    {
        $empleOcupaciones = EmpleOcupacion::all();
        $igssOcupaciones = IgssOcupacion::all();
        return view('ocupacion.edit', compact('ocupacion', 'empleOcupaciones', 'igssOcupaciones'));
    }

    public function update(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_emple_ocupacion' => 'required|exists:emple_ocupacion,id',
            'id_igss_ocupacion' => 'required|exists:igss_ocupacion,id',
        ]);

        $ocupacion->update($request->all());
        return redirect()->route('ocupacion.index')->with('success', 'Ocupación del sistema actualizada exitosamente.');
    }

    public function destroy(Ocupacion $ocupacion)
    {
        $ocupacion->delete();
        return redirect()->route('ocupacion.index')->with('success', 'Ocupación eliminada lógicamente.');
    }
}
