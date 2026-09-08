<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'firmante_rrhh' => 'nullable|string|max:255',
            'razon_social' => 'required|string|max:255',
            'numero_patronal' => 'required|string|max:255'

        ]);

        if ($request->hasFile('logo')) {
            // Guarda la imagen en la carpeta public/logos
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Empresa::create($data);

        return redirect()->route('empresas.index')->with('success', 'Empresa registrada.');
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'firmante_rrhh' => 'nullable|string|max:255',
            'razon_social' => 'required|string|max:255',
            'numero_patronal' => 'required|string|max:255'
        ]);

        if ($request->hasFile('logo')) {
            // Si ya tenía un logo, borramos el viejo del servidor para ahorrar espacio
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            // Guardamos el nuevo
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $empresa->update($data);

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada.');
    }

    // ACA DEBES PEGAR EL NUEVO MÉTODO:
    public function empleados(Empresa $empresa)
    {
        // Cargamos los empleados que pertenecen a esta empresa
        $empresa->load('empleados');
        
        return view('empresas.empleados', compact('empresa'));
    }

    public function reporteMensual(Request $request, Empresa $empresa)
    {
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $detalles = collect(); // Colección vacía por defecto

        // Si el usuario seleccionó un mes y año, hacemos la consulta
        if ($mes && $anio) {
            $detalles = \App\Models\DetalleCarga::with(['empleado', 'carga'])
                // Filtramos que el empleado pertenezca a la empresa (incluso si fue eliminado lógicamente)
                ->whereHas('empleado', function ($query) use ($empresa) {
                    $query->withTrashed()->where('empresa_id', $empresa->id);
                })
                // Filtramos por el mes y año de la carga
                ->whereHas('carga', function ($query) use ($mes, $anio) {
                    $query->where('mes_planilla', $mes)
                          ->where('anio_planilla', $anio);
                })
                ->get();
        }

        return view('empresas.reporte_mensual', compact('empresa', 'detalles', 'mes', 'anio'));
    }
}