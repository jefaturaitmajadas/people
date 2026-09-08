<?php

namespace App\Http\Controllers;

use App\Models\TipoIncidencia;
use Illuminate\Http\Request;

class TipoIncidenciaController extends Controller
{
    /**
     * Muestra el catálogo de tipos de incidencias.
     */
    public function index()
    {
        // Traemos los tipos con el conteo de incidencias vinculadas
        $tiposIncidencia = TipoIncidencia::withCount('incidencias')->get();

        return view('tipos_incidencia.index', compact('tiposIncidencia'));
    }

    /**
     * Muestra el formulario para crear un tipo de incidencia.
     */
    public function create()
    {
        return view('tipos_incidencia.create');
    }

    /**
     * Almacena un nuevo tipo de incidencia en la BD.
     */
    public function store(Request $request)
    {
        // Validamos el nuevo campo porcentaje_descuento
        $request->validate([
            'nombre'               => 'required|string|max:150|unique:tipo_incidencias,nombre',
            'descuenta_salario'    => 'required|boolean',
            'porcentaje_descuento' => 'required|numeric|min:0|max:100',
        ]);

        TipoIncidencia::create([
            'nombre'               => trim($request->nombre),
            'descuenta_salario'    => $request->boolean('descuenta_salario'),
            // Si no descuenta salario, forzamos el porcentaje a 0 para proteger la contabilidad
            'porcentaje_descuento' => $request->boolean('descuenta_salario') ? $request->porcentaje_descuento : 0.00,
        ]);

        return redirect()->route('tipo-incidencias.index')
            ->with('success', 'Tipo de incidencia creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar el tipo de incidencia.
     */
    public function edit(TipoIncidencia $tipoIncidencia)
    {
        return view('tipos_incidencia.edit', compact('tipoIncidencia'));
    }

    /**
     * Actualiza el tipo de incidencia.
     */
    public function update(Request $request, TipoIncidencia $tipoIncidencia)
    {
        $request->validate([
            'nombre'               => 'required|string|max:150|unique:tipo_incidencias,nombre,' . $tipoIncidencia->id,
            'descuenta_salario'    => 'required|boolean',
            'porcentaje_descuento' => 'required|numeric|min:0|max:100',
        ]);

        $tipoIncidencia->update([
            'nombre'               => trim($request->nombre),
            'descuenta_salario'    => $request->boolean('descuenta_salario'),
            // Igual que en la creación, blindamos el dato:
            'porcentaje_descuento' => $request->boolean('descuenta_salario') ? $request->porcentaje_descuento : 0.00,
        ]);

        return redirect()->route('tipo-incidencias.index')
            ->with('success', 'Tipo de incidencia actualizado correctamente.');
    }

    /**
     * Elimina el tipo de incidencia si no tiene registros vinculados.
     */
    public function destroy(TipoIncidencia $tipoIncidencia)
    {
        // Validación de Integridad Referencial
        if ($tipoIncidencia->incidencias()->exists()) {
            return redirect()->route('tipo-incidencias.index')
                ->with('error', 'No se puede eliminar: existen registros de incidencias asignados a este tipo.');
        }

        $tipoIncidencia->delete();

        return redirect()->route('tipo-incidencias.index')
            ->with('success', 'Tipo de incidencia eliminado correctamente.');
    }
}