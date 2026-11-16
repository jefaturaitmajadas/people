<?php

namespace App\Mail;

use App\Models\PlanillaDetalle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Boleta de pago de una planilla generada en el sistema (planilla_detalles).
 */
class BoletaPlanillaMail extends Mailable
{
    use Queueable, SerializesModels;

    public PlanillaDetalle $detalle;

    public function __construct(PlanillaDetalle $detalle)
    {
        $this->detalle = $detalle->loadMissing(['planilla.empresa', 'empleado.puesto', 'empleado.departamento']);
    }

    public function build()
    {
        $empresa = mb_strtoupper($this->detalle->planilla->empresa->nombre ?? 'COLAPERSA', 'UTF-8');

        return $this->subject("Comprobante de Pago - {$empresa} - {$this->detalle->planilla->descripcion}")
                    ->view('emails.boleta_planilla');
    }
}
