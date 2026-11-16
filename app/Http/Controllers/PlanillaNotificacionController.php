<?php

namespace App\Http\Controllers;

use App\Mail\BoletaPlanillaMail;
use App\Models\Planilla;
use App\Models\PlanillaDetalle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Envío por correo de las boletas de pago de una planilla generada.
 */
class PlanillaNotificacionController extends Controller
{
    // La notificación individual es de prueba: siempre llega a este correo, nunca al empleado
    private const CORREO_PRUEBA = 'jefaturait@majadas.com';

    /**
     * Envía la boleta a cada empleado de la planilla (correo corporativo o, si no tiene, personal).
     * Solo toma los que no se han enviado, así que se puede volver a ejecutar si algo falla.
     */
    public function enviarMasivo(Planilla $planilla)
    {
        if ($planilla->estado !== 'cerrada') {
            return back()->with('error', 'Solo se pueden enviar las boletas de una planilla CERRADA. Cierra y aprueba la planilla primero.');
        }

        set_time_limit(0);

        $detalles = $planilla->detalles()
            ->with(['planilla.empresa', 'empleado.puesto', 'empleado.departamento'])
            ->whereNull('notificacion_enviada_at')
            ->get();

        $enviados = 0;
        $sinCorreo = [];
        $fallidos = [];

        foreach ($detalles as $detalle) {
            $empleado = $detalle->empleado;
            $correo = $empleado ? ($empleado->correo_corporativo ?: $empleado->correo_personal) : null;

            if (!$correo) {
                $sinCorreo[] = $empleado->codigo_empleado ?? "detalle {$detalle->id}";
                continue;
            }

            try {
                Mail::to($correo)->send(new BoletaPlanillaMail($detalle));
                $detalle->update(['notificacion_enviada_at' => now()]);
                $enviados++;
            } catch (\Throwable $e) {
                Log::error("Error al enviar boleta de planilla {$planilla->id} a {$correo}: {$e->getMessage()}");
                $fallidos[] = $empleado->codigo_empleado;
            }
        }

        $mensaje = "Boletas enviadas: {$enviados}.";
        if ($sinCorreo) {
            $mensaje .= ' Sin correo registrado (códigos): ' . implode(', ', $sinCorreo) . '.';
        }
        if ($fallidos) {
            $mensaje .= ' Fallaron (códigos): ' . implode(', ', $fallidos) . '. Vuelve a ejecutar el envío para reintentar.';
        }
        if ($detalles->isEmpty()) {
            $mensaje = 'Todas las boletas de esta planilla ya fueron enviadas.';
        }

        return back()->with($sinCorreo || $fallidos ? 'error' : 'success', $mensaje);
    }

    /**
     * Envía la boleta de un empleado al correo de prueba para revisar cómo se visualiza.
     * No marca la boleta como enviada.
     */
    public function enviarIndividual(PlanillaDetalle $detalle)
    {
        try {
            Mail::to(self::CORREO_PRUEBA)->send(new BoletaPlanillaMail($detalle));
        } catch (\Throwable $e) {
            Log::error("Error al enviar boleta de prueba (detalle {$detalle->id}): {$e->getMessage()}");

            return back()->with('error', 'No se pudo enviar la boleta de prueba: ' . $e->getMessage());
        }

        $codigo = $detalle->empleado->codigo_empleado ?? '';

        return back()->with('success', "Boleta del empleado {$codigo} enviada a " . self::CORREO_PRUEBA . ' para revisión.');
    }
}
