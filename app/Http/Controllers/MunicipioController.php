<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\IgssDepartamento;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function index()
    {
        // Cargamos la relación para optimizar la consulta
        $municipios = Municipio::with('igssDepartamento')->get();
        return view('municipio.index', compact('municipios'));
    }

    public function create()
    {
        $departamentos = IgssDepartamento::all();
        return view('municipio.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_empl' => 'required|integer',
            'codigo_igss' => 'required|string|max:255',
            'id_igss_departamento' => 'required|exists:igss_departamento,id',
        ]);

        Municipio::create($request->all());
        return redirect()->route('municipio.index')->with('success', 'Municipio registrado exitosamente.');
    }

    public function edit(Municipio $municipio)
    {
        $departamentos = IgssDepartamento::all();
        return view('municipio.edit', compact('municipio', 'departamentos'));
    }

    public function update(Request $request, Municipio $municipio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_empl' => 'required|integer',
            'codigo_igss' => 'required|string|max:255',
            'id_igss_departamento' => 'required|exists:igss_departamento,id',
        ]);

        $municipio->update($request->all());
        return redirect()->route('municipio.index')->with('success', 'Municipio actualizado exitosamente.');
    }

    public function destroy(Municipio $municipio)
    {
        $municipio->delete();
        return redirect()->route('municipio.index')->with('success', 'Municipio eliminado lógicamente.');
    }
}
