<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Empleado;

class FelizCumpleanosMail extends Mailable
{
    use Queueable, SerializesModels;

    public $empleado;
    public $empresa;

    public function __construct(Empleado $empleado)
    {
        $this->empleado = $empleado;
        // Sin global scope: el comando programado corre fuera de la sesión
        $this->empresa = \App\Models\Empresa::find($empleado->empresa_id);
    }

    public function build()
    {
        return $this->subject('¡Feliz Cumpleaños, ' . $this->empleado->primer_nombre . '! 🎉')
                    ->view('emails.feliz_cumpleanos');
    }
}
