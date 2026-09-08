<?php

namespace App\Http\Controllers;

use App\Models\IgssTipoPlanilla;
use App\Models\Empresa;
use App\Models\IgssDepartamento;
use App\Models\IgssActividadEconomica;
use Illuminate\Http\Request;

class IgssTipoPlanillaController extends Controller
{
    public function index()
    {
        $planillas = IgssTipoPlanilla::with(['empresa', 'igssDepartamento', 'igssActividadEconomica'])->get();
        return view('igss_tipo_planilla.index', compact('planillas'));
    }

    public function create()
    {
        $empresas = Empresa::all();
        $departamentos = IgssDepartamento::all();
        $actividades = IgssActividadEconomica::all();
        
        return view('igss_tipo_planilla.create', compact('empresas', 'departamentos', 'actividades'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        // Generar la cadena concatenada
        $actividad = IgssActividadEconomica::findOrFail($request->id_actividad);
        $concatenacion = implode('|', [
            $request->identificacion,
            $request->nombre,
            $request->tipo_afiliado,
            $request->periodo,
            $request->id_igss_departamento,
            $actividad->clasificacion, // Extraído de la relación
            $request->clase_planilla,
            $request->tiempo_contrato
        ]);

        $data = $request->all();
        $data['cadena_concatenada'] = $concatenacion;

        IgssTipoPlanilla::create($data);
        return redirect()->route('tipos-planilla.index')->with('success', 'Tipo de Planilla registrado exitosamente.');
    }

    public function edit(IgssTipoPlanilla $tipo_planilla)
    {
        $empresas = Empresa::all();
        $departamentos = IgssDepartamento::all();
        $actividades = IgssActividadEconomica::all();
        
        return view('igss_tipo_planilla.edit', compact('tipo_planilla', 'empresas', 'departamentos', 'actividades'));
    }

    public function update(Request $request, IgssTipoPlanilla $tipo_planilla)
    {
        $this->validateRequest($request);

        // Re-generar la cadena concatenada en caso de cambios
        $actividad = IgssActividadEconomica::findOrFail($request->id_actividad);
        $concatenacion = implode('|', [
            $request->identificacion,
            $request->nombre,
            $request->tipo_afiliado,
            $request->periodo,
            $request->id_igss_departamento,
            $actividad->clasificacion,
            $request->clase_planilla,
            $request->tiempo_contrato
        ]);

        $data = $request->all();
        $data['cadena_concatenada'] = $concatenacion;

        $tipo_planilla->update($data);
        return redirect()->route('tipos-planilla.index')->with('success', 'Tipo de Planilla actualizado exitosamente.');
    }

    public function destroy(IgssTipoPlanilla $tipo_planilla)
    {
        $tipo_planilla->delete();
        return redirect()->route('tipos-planilla.index')->with('success', 'Tipo de Planilla eliminado lógicamente.');
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'id_empresa' => 'required|exists:empresas,id',
            'identificacion' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'tipo_afiliado' => 'required|in:S,C',
            'periodo' => 'required|in:M,C,S',
            'id_igss_departamento' => 'required|exists:igss_departamento,id',
            'id_actividad' => 'required|exists:igss_actividad_economica,id',
            'clase_planilla' => 'required|in:N,V',
            'tiempo_contrato' => 'required|in:TP,TC',
        ]);
    }
}