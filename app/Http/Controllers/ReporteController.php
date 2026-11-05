<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Exports\ReporteEmpleadosExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function reporteEmpleador(Request $request)
    {
        $empresa = Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'Debes tener una empresa activa.');
        }

        $anioSeleccionado = $request->input('anio', date('Y'));
        $aniosDisponibles = range(date('Y'), 2015);

        $empleados = Empleado::with(['departamento', 'puesto'])
            // SUMA DE HORAS EXTRAS
            ->withSum(['planillasDetalles' => function ($query) use ($anioSeleccionado) {
                $query->whereYear('created_at', $anioSeleccionado);
            }], 'total_horas_extras')
            
            // NUEVO: SUMA DE INGRESOS ADICIONALES
            ->withSum(['planillasDetalles' => function ($query) use ($anioSeleccionado) {
                $query->whereYear('created_at', $anioSeleccionado);
            }], 'total_ingresos_adicionales')
            
            ->where('fecha_inicio', '<=', $anioSeleccionado . '-12-31')
            ->where(function ($query) use ($anioSeleccionado) {
                $query->whereNull('fecha_salida')
                      ->orWhere('fecha_salida', '>=', $anioSeleccionado . '-01-01');
            })
            ->orderBy('primer_nombre', 'asc')
            ->get();

        return view('reportes.empleador', compact('empresa', 'empleados', 'anioSeleccionado', 'aniosDisponibles'));
    }

    public function reporteVacaciones()
    {
        $empresa = \App\Models\Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'Debes tener una empresa activa.');
        }

        // Cargamos empleados activos y sus vacaciones
        $empleados = \App\Models\Empleado::with('vacaciones')
            ->whereNull('fecha_salida')
            ->orderBy('primer_nombre', 'asc')
            ->get();

        return view('reportes.vacaciones', compact('empresa', 'empleados'));
    }

    public function exportarVacacionesExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ReporteVacacionesExport, 
            'Reporte_Saldos_Vacaciones.xlsx'
        );
    }

    public function reporteEmpleados(Request $request)
    {
        $empresa = Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'Debes tener una empresa activa.');
        }

        $estado = $this->estadoSolicitado($request);
        $buscar = $this->busquedaSolicitada($request);
        $reporte = new ReporteEmpleadosExport($estado, $buscar);

        return view('reportes.empleados', [
            'empresa' => $empresa,
            'estado' => $estado,
            'buscar' => $buscar,
            'estados' => ReporteEmpleadosExport::ESTADOS,
            'encabezados' => $reporte->headings(),
            'filas' => $reporte->collection()->map(fn ($empleado) => $reporte->map($empleado)),
        ]);
    }

    public function exportarEmpleadosExcel(Request $request)
    {
        if (!Empresa::find(session('empresa_activa_id'))) {
            return redirect()->route('dashboard')->with('error', 'Debes tener una empresa activa.');
        }

        $estado = $this->estadoSolicitado($request);
        $sufijo = str_replace(' ', '_', ReporteEmpleadosExport::ESTADOS[$estado]);

        return Excel::download(new ReporteEmpleadosExport($estado, $this->busquedaSolicitada($request)), "Reporte_General_Empleados_{$sufijo}.xlsx");
    }

    private function estadoSolicitado(Request $request): string
    {
        $estado = $request->query('estado');

        return is_string($estado) && array_key_exists($estado, ReporteEmpleadosExport::ESTADOS) ? $estado : 'todos';
    }

    private function busquedaSolicitada(Request $request): string
    {
        $buscar = $request->query('buscar');

        return is_string($buscar) ? trim($buscar) : '';
    }
}