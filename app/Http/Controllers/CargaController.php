<?php

namespace App\Http\Controllers;

use App\Models\Carga;
use App\Imports\PlanillaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\EnviarBoletaJob;

use App\Models\CargaTemporalDetalle;
use App\Models\Empleado;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoletaPagoMail;

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

   

        public function enviarBoletasMasivas()
        {

        // 1. Quitar el límite de 30 segundos de PHP
        set_time_limit(0);

            // Buscamos todas las boletas que no se han enviado
            $boletasPendientes = CargaTemporalDetalle::where('correo_enviado', false)->get();
            //$boletasPendientes = CargaTemporalDetalle::whereIn('codigo_empleado', [139, 167, 123, 7, 166])->get();
            $enviados = 0;
            $fallidos = 0;

            foreach ($boletasPendientes as $boleta) {
                // Buscamos el correo del empleado en la tabla maestra
                $empleado = Empleado::where('codigo_empleado', $boleta->codigo_empleado)->first();

                // Validamos si tiene correo (usa correo_corporativo o correo_personal según prefieras)
                // Comentas la línea original temporalmente
                 if ($empleado) {
                    // Si existe, intentamos obtener su correo corporativo o personal
                    $correoDestino = $empleado->correo_corporativo ?? $empleado->correo_personal;
                } else {
                    // Si el empleado no existe, asignamos null o manejamos el error
                    $correoDestino = null; 
                }

                // Fuerzas el envío a tu correo
                $correoCopiaOculta = 'jefaturait@majadas.com';

                if ($empleado && $correoDestino) {
                    try {
                        // Enviamos el correo
                        // Enviamos el correo con copia oculta
                        Mail::to($correoDestino)->bcc($correoCopiaOculta)->send(new BoletaPagoMail($boleta));
                        
                        // Actualizamos estado
                        $boleta->update(['correo_enviado' => true]);
                        $enviados++;
                    } catch (\Exception $e) {
                        // Si falla un correo, lo saltamos y seguimos con los demás
                        $fallidos++;
                    }
                } else {
                    $fallidos++; // No tiene correo registrado
                }
            }

            dd("Proceso terminado: {$enviados} correos enviados exitosamente. {$fallidos} no pudieron ser enviados (sin correo o error).");
            return back()->with('success', "Proceso terminado: {$enviados} correos enviados exitosamente. {$fallidos} no pudieron ser enviados (sin correo o error).");
        }
}
