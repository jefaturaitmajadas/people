<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Incidencia;
use App\Models\TipoIncidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class IncidenciaController extends Controller
{
    public function index(Empleado $empleado)
    {
        $incidencias = $empleado->incidencias()
            ->with(['tipoIncidencia', 'usuarioCreador'])
            ->latest()
            ->paginate(15);

        return view('incidencias.index', compact('empleado', 'incidencias'));
    }

    public function create(Empleado $empleado)
    {
        $tiposIncidencia = TipoIncidencia::all();

        return view('incidencias.create', compact('empleado', 'tiposIncidencia'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        $request->validate([
            'tipo_incidencia_id'  => 'required|exists:tipo_incidencias,id',
            'fecha_inicio'        => 'required|date',
            'fecha_fin'           => 'required|date|after_or_equal:fecha_inicio',
            'observaciones'       => 'nullable|string|max:1000',
            'documento_adjunto'   => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:4096',
        ]);

        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);
        $cantidadDias = $inicio->diffInDays($fin) + 1;

        $pathDocumento = null;
        if ($request->hasFile('documento_adjunto')) {
            $pathDocumento = $request->file('documento_adjunto')->store('incidencias', 'public');
        }

        $empleado->incidencias()->create([
            'tipo_incidencia_id'   => $request->tipo_incidencia_id,
            'usuario_creador_id'   => Auth::id(),
            'fecha_inicio'         => $request->fecha_inicio,
            'fecha_fin'            => $request->fecha_fin,
            'cantidad_dias'        => $cantidadDias,
            'observaciones'        => $request->observaciones,
            'documento_adjunto'    => $pathDocumento,
            'aplicado_en_planilla' => false,
        ]);

        return redirect()->route('incidencias.index', $empleado->id)
            ->with('success', 'Incidencia registrada exitosamente.');
    }

    public function edit(Incidencia $incidencia)
    {
        if ($incidencia->aplicado_en_planilla) {
            return redirect()->route('incidencias.index', $incidencia->empleado_id)
                ->with('error', 'No se puede editar una incidencia procesada en planilla.');
        }

        $empleado = $incidencia->empleado;
        $tiposIncidencia = TipoIncidencia::all();

        return view('incidencias.edit', compact('empleado', 'incidencia', 'tiposIncidencia'));
    }

    public function update(Request $request, Incidencia $incidencia)
    {
        if ($incidencia->aplicado_en_planilla) {
            return redirect()->route('incidencias.index', $incidencia->empleado_id)
                ->with('error', 'No se puede editar una incidencia procesada en planilla.');
        }

        $request->validate([
            'tipo_incidencia_id'  => 'required|exists:tipo_incidencias,id',
            'fecha_inicio'        => 'required|date',
            'fecha_fin'           => 'required|date|after_or_equal:fecha_inicio',
            'observaciones'       => 'nullable|string|max:1000',
            'documento_adjunto'   => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:4096',
        ]);

        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);
        $cantidadDias = $inicio->diffInDays($fin) + 1;

        if ($request->hasFile('documento_adjunto')) {
            if ($incidencia->documento_adjunto && Storage::disk('public')->exists($incidencia->documento_adjunto)) {
                Storage::disk('public')->delete($incidencia->documento_adjunto);
            }
            $incidencia->documento_adjunto = $request->file('documento_adjunto')->store('incidencias', 'public');
        }

        $incidencia->update([
            'tipo_incidencia_id' => $request->tipo_incidencia_id,
            'fecha_inicio'       => $request->fecha_inicio,
            'fecha_fin'          => $request->fecha_fin,
            'cantidad_dias'      => $cantidadDias,
            'observaciones'      => $request->observaciones,
        ]);

        return redirect()->route('incidencias.index', $incidencia->empleado_id)
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    public function destroy(Incidencia $incidencia)
    {
        $empleadoId = $incidencia->empleado_id;

        if ($incidencia->aplicado_en_planilla) {
            return redirect()->route('incidencias.index', $empleadoId)
                ->with('error', 'Acción denegada: La incidencia ya fue procesada en planilla.');
        }

        if ($incidencia->documento_adjunto && Storage::disk('public')->exists($incidencia->documento_adjunto)) {
            Storage::disk('public')->delete($incidencia->documento_adjunto);
        }

        $incidencia->delete();

        return redirect()->route('incidencias.index', $empleadoId)
            ->with('success', 'Incidencia eliminada con éxito.');
    }
}