<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Mail\FelizCumpleanosMail;

class CumpleanosController extends Controller
{
    public const MESES = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    // Módulo 1: Reporte tabular de cumpleañeros del mes
    public function index(Request $request)
    {
        $datos = $this->obtenerDatos($request);

        if (!$datos) {
            return redirect()->route('dashboard')->with('error', 'Debes tener una empresa activa.');
        }

        return view('cumpleanos.index', $datos);
    }

    // Módulo 2: Arte de cumpleañeros del mes para el correo general de RRHH
    public function arte(Request $request)
    {
        $datos = $this->obtenerDatos($request);

        if (!$datos) {
            return redirect()->route('dashboard')->with('error', 'Debes tener una empresa activa.');
        }

        return view('cumpleanos.arte', $datos);
    }

    // Vista previa del correo individual de felicitación (Módulo 3)
    public function previewCorreo(Empleado $empleado)
    {
        return new FelizCumpleanosMail($empleado);
    }

    private function obtenerDatos(Request $request)
    {
        $empresa = Empresa::find(session('empresa_activa_id'));

        if (!$empresa) {
            return null;
        }

        $mesSeleccionado = (int) $request->input('mes', date('n'));
        $anioSeleccionado = (int) $request->input('anio', date('Y'));

        if ($mesSeleccionado < 1 || $mesSeleccionado > 12) {
            $mesSeleccionado = (int) date('n');
        }

        $aniosDisponibles = range(date('Y') + 1, 2015);
        $meses = self::MESES;

        $empleados = Empleado::with(['departamento', 'puesto'])
            ->cumpleanerosDelMes($mesSeleccionado, $anioSeleccionado)
            ->get();

        return compact('empresa', 'empleados', 'mesSeleccionado', 'anioSeleccionado', 'aniosDisponibles', 'meses');
    }
}
