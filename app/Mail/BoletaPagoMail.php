<?php

namespace App\Mail;

use App\Models\DetalleCarga;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BoletaPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detalle;

    public function __construct(DetalleCarga $detalle)
    {
        $this->detalle = $detalle;
    }

    public function build()
    {
        // Generamos el PDF usando una vista Blade (la crearemos después)
        $pdf = Pdf::loadView('pdfs.boleta', ['detalle' => $this->detalle]);

        return $this->subject('Tu Boleta de Pago - ' . $this->detalle->carga->mes_planilla)
                    ->view('emails.cuerpo_correo') // El texto del correo
                    ->attachData($pdf->output(), 'Boleta_Pago.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}