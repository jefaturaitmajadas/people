<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CargaTemporalDetalle;

class BoletaPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detalle;

    public function __construct(CargaTemporalDetalle $detalle)
    {
        $this->detalle = $detalle;
    }

    public function build()
    {
        return $this->subject('Comprobante de Pago - COLAPERSA')
                    ->view('emails.boleta_pago');
    }
}