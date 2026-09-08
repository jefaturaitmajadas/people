<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\DocumentoEmpleado;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importante para manejar archivos

class DocumentoEmpleadoController extends Controller
{
    public function index(Empleado $empleado)
    {
        // Traemos los documentos con su relación tipoDocumento
        $documentos = $empleado->documentos()->with('tipoDocumento')->get();
        return view('documentos.index', compact('empleado', 'documentos'));
    }

    public function create(Empleado $empleado)
    {
        $tipos_documento = TipoDocumento::all();
        return view('documentos.create', compact('empleado', 'tipos_documento'));
    }

    public function store(Request $request, Empleado $empleado)
    {
        // 1. Validación
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documento,id',
            'descripcion' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'observaciones' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' // Tamaño máximo 5MB
        ]);

        // 2. Preparar los datos
        $data = $request->all();
        $data['empleado_id'] = $empleado->id;

        // 3. Manejo del archivo adjunto
        if ($request->hasFile('archivo')) {
            // Guarda el archivo en la carpeta storage/app/public/documentos_empleados
            $data['archivo_ruta'] = $request->file('archivo')->store('documentos_empleados', 'public');
        }

        // 4. Crear el registro
        DocumentoEmpleado::create($data);

        return redirect()->route('documentos.index', $empleado->id)
                         ->with('success', 'Documento registrado y adjuntado exitosamente.');
    }

    public function edit(DocumentoEmpleado $documento)
    {
        $documento->load('empleado');
        $tipos_documento = TipoDocumento::all();
        return view('documentos.edit', compact('documento', 'tipos_documento'));
    }

    public function update(Request $request, DocumentoEmpleado $documento)
    {
        // 1. Validación
        $request->validate([
            'tipo_documento_id' => 'required|exists:tipo_documento,id',
            'descripcion' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'observaciones' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        // 2. Preparar los datos
        $data = $request->all();

        // 3. Manejo del nuevo archivo adjunto (si se sube uno)
        if ($request->hasFile('archivo')) {
            // Eliminar el archivo anterior físicamente del servidor si existe
            if ($documento->archivo_ruta) {
                Storage::disk('public')->delete($documento->archivo_ruta);
            }
            // Guardar el nuevo archivo
            $data['archivo_ruta'] = $request->file('archivo')->store('documentos_empleados', 'public');
        }

        // 4. Actualizar el registro
        $documento->update($data);

        return redirect()->route('documentos.index', $documento->empleado_id)
                         ->with('success', 'Documento actualizado exitosamente.');
    }

    public function destroy(DocumentoEmpleado $documento)
    {
        $empleado_id = $documento->empleado_id;

        // Como estás usando SoftDeletes (eliminación lógica), es mejor NO borrar el archivo físico 
        // para que si restauras el registro, el archivo siga ahí.
        // Si en tu lógica de negocio prefieres borrar el archivo de inmediato para ahorrar espacio, 
        // descomenta las siguientes 3 líneas:
        /*
        if ($documento->archivo_ruta) {
            Storage::disk('public')->delete($documento->archivo_ruta);
        }
        */

        $documento->delete();

        return redirect()->route('documentos.index', $empleado_id)
                         ->with('success', 'Documento eliminado exitosamente.');
    }
}