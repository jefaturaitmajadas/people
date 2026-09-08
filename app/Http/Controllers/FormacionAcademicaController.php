<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\FormacionAcademica;
use App\Models\GradoAcademico; // Importamos el modelo relacionado
use Illuminate\Http\Request;

class FormacionAcademicaController extends Controller
{
    public function index(Empleado $empleado)
    {
        // Traemos las formaciones con su relación gradoAcademico
        $formaciones = $empleado->formacionesAcademicas()->with('gradoAcademico')->get();
        return view('formacion_academica.index', compact('empleado', 'formaciones'));
    }

    public function create(Empleado $empleado)
    {
        $grados = GradoAcademico::all();
        return view('formacion_academica.create', compact('empleado', 'grados'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'grado_academico_id' => 'required|exists:grado_academico,id',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $data = $request->all();
        $data['empleado_id'] = $empleado->id;
        
        FormacionAcademica::create($data);
        return redirect()->route('formacion-academica.index', $empleado->id)
                         ->with('success', 'Formación académica registrada exitosamente.');
    }

    public function edit(FormacionAcademica $formacion)
    {
        $formacion->load('empleado');
        $grados = GradoAcademico::all();
        return view('formacion_academica.edit', compact('formacion', 'grados'));
    }

    public function update(Request $request, FormacionAcademica $formacion)
    {
        $request->validate([
            'grado_academico_id' => 'required|exists:grado_academico,id',
            'institucion' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $formacion->update($request->all());
        return redirect()->route('formacion-academica.index', $formacion->empleado_id)
                         ->with('success', 'Formación académica actualizada exitosamente.');
    }

    public function destroy(FormacionAcademica $formacion)
    {
        $empleado_id = $formacion->empleado_id;
        $formacion->delete();
        return redirect()->route('formacion-academica.index', $empleado_id)
                         ->with('success', 'Formación académica eliminada.');
    }
}
