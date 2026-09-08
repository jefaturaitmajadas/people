<?php

namespace App\Http\Controllers;

use App\Models\Profesion;
use Illuminate\Http\Request;

class ProfesionController extends Controller
{
    public function index()
    {
        // Traemos todas las profesiones ordenadas alfabéticamente
        $profesiones = Profesion::orderBy('nombre', 'asc')->get();
        return view('profesiones.index', compact('profesiones'));
    }

    public function create()
    {
        return view('profesiones.create');
    }

    public function store(Request $request)
    {
        // Validamos que el nombre sea obligatorio y no se repita en la tabla
        $request->validate([
            'nombre' => 'required|string|max:255|unique:profesiones,nombre'
        ]);

        Profesion::create([
            'nombre' => mb_strtoupper($request->nombre, 'UTF-8') // Forzamos mayúsculas
        ]);

        return redirect()->route('profesiones.index')->with('success', 'Profesión registrada correctamente.');
    }

    public function edit(Profesion $profesion)
    {
        return view('profesiones.edit', compact('profesion'));
    }

    public function update(Request $request, Profesion $profesion)
    {
        // Validamos que el nombre sea único, exceptuando la profesión que estamos editando
        $request->validate([
            'nombre' => 'required|string|max:255|unique:profesiones,nombre,' . $profesion->id
        ]);

        $profesion->update([
            'nombre' => mb_strtoupper($request->nombre, 'UTF-8')
        ]);

        return redirect()->route('profesiones.index')->with('success', 'Profesión actualizada correctamente.');
    }

    public function destroy(Profesion $profesion)
    {
        // Validación de seguridad: Evitar borrar profesiones en uso
        if ($profesion->empleados()->count() > 0) {
            return back()->with('error', 'No puedes eliminar esta profesión porque ya hay empleados registrados con ella.');
        }

        $profesion->delete();
        return redirect()->route('profesiones.index')->with('success', 'Profesión eliminada correctamente.');
    }
}