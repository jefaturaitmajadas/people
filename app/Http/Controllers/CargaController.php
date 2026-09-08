<?php

namespace App\Http\Controllers;

use App\Models\Carga;
use App\Imports\PlanillaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\EnviarBoletaJob;

class CargaController extends Controller
{
    public function index()
    {
        // Listamos las cargas ordenadas por fecha
        $cargas = Carga::orderBy('created_at', 'desc')->get();
        return view('cargas.index', compact('cargas'));
    }

    public function create()
    {
        return view('cargas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_carga'   => 'required|date',
            'mes_planilla'  => 'required|string',
            'anio_planilla' => 'required|integer',
            'archivo_excel' => 'required|mimes:xlsx,csv,xls',
        ]);

        // 1. Crear el registro Maestro (Carga)
        $carga = Carga::create([
            'fecha_carga'   => $request->fecha_carga,
            'mes_planilla'  => $request->mes_planilla,
            'anio_planilla' => $request->anio_planilla,
        ]);

        // 2. Ejecutar la importación pasando el ID de la carga
        Excel::import(new PlanillaImport($carga->id), $request->file('archivo_excel'));

        return redirect()->route('cargas.show', $carga->id)
                         ->with('success', 'Planilla cargada exitosamente.');
    }

    public function show(Carga $carga)
    {
        // Cargamos la carga con sus detalles y los empleados asociados
        $carga->load('detalleCargas.empleado');
        return view('cargas.show', compact('carga'));
    }

    public function destroy(Carga $carga)
    {
        // Al tener cascade on delete en la migración, o usando eventos del modelo, 
        // se eliminarán los detalles también.
        $carga->delete();
        return redirect()->route('cargas.index')->with('success', 'Carga y sus detalles eliminados.');
    }

    public function enviarNotificaciones(Carga $carga)
    {
        // Cargamos los detalles para iterar sobre ellos
        $carga->load('detalleCargas.empleado');

        foreach ($carga->detalleCargas as $detalle) {
            // Despachamos un Job por cada línea de detalle
            EnviarBoletaJob::dispatch($detalle);
        }

        return back()->with('success', 'Las notificaciones se están enviando en segundo plano. Los empleados recibirán su correo pronto.');
    }
}
