<?php

namespace App\Jobs;

use App\Models\DetalleCarga;
use App\Mail\BoletaPagoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarBoletaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $detalle;

    public function __construct(DetalleCarga $detalle)
    {
        $this->detalle = $detalle;
    }

    public function handle(): void
    {
        // Verificamos que el empleado tenga un correo válido antes de enviar
        if ($this->detalle->empleado && $this->detalle->empleado->correo) {
            Mail::to($this->detalle->empleado->correo)->send(new BoletaPagoMail($this->detalle));
        }
    }
}
